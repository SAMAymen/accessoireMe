# STM Importer

Plugin for importing the demo content.

## WP CLI

Core StylemixThemes Namespace:

```
$ wp stm
```

Install Layout Plugins through WP CLI. Usage example:

```
$ wp stm install <layout> [--builder=<builder>]
```

Run demo import through WP CLI. Usage example:

```
$ wp stm import <layout> [--builder=<builder>] [--data=<theme_options,content,widgets>] [--no-media]
```

This command only imports Demo Content.

**REMEMBER: Sliders won't be imported as Revolution Slider doesn't support WP CLI**