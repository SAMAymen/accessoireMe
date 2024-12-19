jQuery(document).ready(function ($) {
  let isUpdating = false;

  function setBeforeUnloadListener() {
    $(window).on('beforeunload', function () {
      if (isUpdating) {
        return 'Updates are still in progress. Are you sure you want to leave this page?';
      }
    });
  }

  function removeBeforeUnloadListener() {
    $(window).off('beforeunload');
  }

  $(".stm-button-bulk-update").on("click", function (e) {
    e.preventDefault();
    const button = $(this);

    if (isUpdating || button.prop("disabled")) {
      return;
    }

    isUpdating = true;
    button.prop("disabled", true).addClass('disabled');

    // Save original button text and update with data-updating text
    const originalText = button.text();
    button.data("original-text", originalText);
    button.text(button.data("updating"));

    // Set beforeunload listener
    setBeforeUnloadListener();

    $('.notice .text-wrap ul li').removeClass('bulk-update-error');
    $(".notices-error-message").hide();

    const plugins = adminNotices.plugins;
    const themes = adminNotices.themes;

    function handleError(listItem) {
      listItem.removeClass("bulk-update-plugin-loading bulk-update-theme-loading");
      listItem.addClass("bulk-update-error");
      $(".notices-error-message").show();
      isUpdating = false;
      button.prop('disabled', false).removeClass('disabled');

      // Restore original button text
      button.text(button.data("original-text"));

      // Remove beforeunload listener
      removeBeforeUnloadListener();
    }

    function updatePlugins(pluginSlugs) {
      if (pluginSlugs.length === 0) {
        // Start updating themes when plugins are done
        updateThemes(Object.keys(themes));
        return;
      }

      const pluginSlug = pluginSlugs.shift();
      const pluginName = plugins[pluginSlug];
      const listItem = $('[data-plugin-slug="' + pluginSlug + '"]');

      listItem.removeClass("bulk-update-error").addClass("bulk-update-plugin-loading");

      $.ajax({
        url: adminNotices.ajax_url,
        type: "POST",
        dataType: "text",
        data: {
          action: "admin_notices_update_plugins",
          plugins: { [pluginSlug]: pluginName },
          nonce: adminNotices.nonce,
        },
        success: function (response) {
          const jsonStartIndex = response.indexOf("{");

          if (jsonStartIndex !== -1) {
            const jsonResponse = response.substring(jsonStartIndex);

            try {
              const parsedResponse = JSON.parse(jsonResponse);

              if (parsedResponse.success) {
                const results = parsedResponse.data;

                if (results[pluginSlug] === true) {
                  listItem
                    .removeClass("bulk-update-plugin-loading")
                    .addClass("bulk-update-plugin-install");
                } else {
                  handleError(listItem);
                  return;
                }
              }
            } catch (e) {
              handleError(listItem);
              return;
            }
          } else {
            handleError(listItem);
            return;
          }
        },
        error: function (xhr, status, error) {
          handleError(listItem);
          return;
        },
        complete: function () {
          // Continue updating plugins recursively
          updatePlugins(pluginSlugs);
        },
      });
    }

    function updateThemes(themeSlugs) {
      if (themeSlugs.length === 0) {
        // All updates are done, keep the button disabled
        isUpdating = false;

        // Restore original button text
        button.text(button.data("original-text"));

        // Remove beforeunload listener
        removeBeforeUnloadListener();
        return;
      }

      const themeSlug = themeSlugs.shift();
      const listItem = $('[data-theme-slug="' + themeSlug + '"]');

      listItem.removeClass("bulk-update-error").addClass("bulk-update-theme-loading");

      $.ajax({
        url: adminNotices.ajax_url,
        type: "POST",
        dataType: "text",
        data: {
          action: "admin_notices_update_themes",
          themes: { [themeSlug]: themes[themeSlug] },
          nonce: adminNotices.nonce,
        },
        success: function (response) {
          const jsonStartIndex = response.indexOf("{");

          if (jsonStartIndex !== -1) {
            const jsonResponse = response.substring(jsonStartIndex);

            try {
              const parsedResponse = JSON.parse(jsonResponse);

              if (parsedResponse.success) {
                const results = parsedResponse.data;

                if (results[themeSlug] === true) {
                  listItem
                    .removeClass("bulk-update-theme-loading")
                    .addClass("bulk-update-theme-install");
                } else {
                  handleError(listItem);
                  return;
                }
              }
            } catch (e) {
              handleError(listItem);
              return;
            }
          } else {
            handleError(listItem);
            return;
          }
        },
        error: function (xhr, status, error) {
          handleError(listItem);
          return;
        },
        complete: function () {
          // Continue updating themes recursively
          updateThemes(themeSlugs);
        },
      });
    }

    // Start updating plugins
    updatePlugins(Object.keys(plugins));
  });
});
