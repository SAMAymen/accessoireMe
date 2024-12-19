if (typeof stm_listing_form_listing != "undefined") {
    Vue.component('tinymce', VueEasyTinyMCE);
    Vue.component('date-picker', DatePicker.default);
    Vue.component('stm-location');

    new Vue({
        el: '#stm-listing-form-listing',
        data: {
            id: null,
            action: null,
            step: "form",
            return_url: null,
            listing_type: null,
            loading: false,
            message: null,
            formData: null,
            status: null,
            title: null,
            planAccess: false,
            featuredAccess: false,
            listing_plan: null,
            listing_plan_one_time: false,
            listing_plan_select: 'none',
            feature_plan_select: 0,
            user_plans: [],
            feature_plans: [],
            errors: [],
            response: null,
            build_data: [],
            images: null,
            attributes: [],
            feature_image: null,
            post_thumbnail_id: null,
            tinymcePlugins: [
                'advlist autolink lists link textcolor',
                'searchreplace visualblocks code',
                'insertdatetime media table contextmenu paste code directionality template colorpicker textpattern'
            ],
            tinymceToolbar1: 'undo redo | bold italic strikethrough | forecolor backcolor | template link | bullist numlist | ltr rtl | removeformat',
            tinymceToolbar2: '',
            tinymceOtherOptions: {
                height: 350,
            }
        },
        created() {
            var vm = this;
            this.id = stm_listing_form_listing.id;
            this.title = stm_listing_form_listing.title;

            // Init Listing plan select
            if (typeof stm_listing_form_listing.listing_plan_select != "undefined") {
                this.listing_plan_select = stm_listing_form_listing.listing_plan_select;
            }

            // Init Feature plan select
            if (typeof stm_listing_form_listing.feature_plan_select != "undefined") {
                this.feature_plan_select = stm_listing_form_listing.feature_plan_select;
            }

            // Init listing plan
            if (typeof stm_listing_form_listing.listing_plan != "undefined") {
                this.listing_plan = stm_listing_form_listing.listing_plan;
            }

            // Init return url
            if (typeof stm_listing_form_listing.return_url != "undefined") {
                this.return_url = stm_listing_form_listing.return_url;
            }

            // Init User plans
            if (typeof stm_listing_form_listing.user_plans != "undefined") {
                this.user_plans = stm_listing_form_listing.user_plans;
            }

            // Init feature plans
            if (typeof stm_listing_form_listing.feature_plans != "undefined") {
                this.feature_plans = stm_listing_form_listing.feature_plans;
            }

            // Init action
            if (typeof stm_listing_form_listing.action != "undefined") {
                this.action = stm_listing_form_listing.action;
            }

            // Init feature image
            if (typeof stm_listing_form_listing.feature_image != "undefined" && stm_listing_form_listing.feature_image) {
                this.feature_image = stm_listing_form_listing.feature_image;
                this.feature_image.id = true;
            }

            this.attributes = stm_listing_form_listing.attributes
            this.listing_type = stm_listing_form_listing.listing_type
            this.user_plans.forEach(function (item) {
                if (item.id == vm.listing_plan_select && item.payment_type === "one_time")
                    vm.listing_plan_one_time = true;
            });
        },
        methods: {
            generateRandomId: function () {
                return parseFloat(Math.round(Math.random() * 100) / 100).toFixed(4) * 1000 + '_' + Date.now();
            },
            select_limit_plan: function (user_plan) {

                if (this.listing_plan_one_time || (user_plan.listing_limit == user_plan.use_listing_limit && user_plan.id != this.listing_plan_select))
                    return;

                if (user_plan.id == this.listing_plan_select) {
                    this.listing_plan_select = "none";
                    user_plan.use_listing_limit--;
                    return;
                }

                if (this.listing_plan_select != "none") {
                    for (key in this.user_plans) {
                        if (this.user_plans[key].id == this.listing_plan_select) {
                            this.user_plans[key].use_listing_limit--;
                        }
                    }
                }

                user_plan.use_listing_limit++;
                this.listing_plan_select = user_plan.id;
            },
            select_feature_plan: function (user_plan) {
                if (user_plan.feature_limit == user_plan.use_feature_limit && user_plan.id != this.feature_plan_select)
                    return;
                if (user_plan.id == this.feature_plan_select) {
                    this.feature_plan_select = 0;
                    user_plan.use_feature_limit--;
                    return;
                }
                if (this.feature_plan_select != 0) {
                    for (key in this.user_plans) {
                        if (this.user_plans[key].id == this.feature_plan_select) {
                            this.user_plans[key].use_feature_limit--;
                        }
                    }
                }
                user_plan.use_feature_limit++;
                this.feature_plan_select = user_plan.id;
            },
            checkDisabledPlan: function (plan) {

                if (!plan.expired)
                    return true;

                if (plan.use < plan.available)
                    return false;

                if (stm_listing_form_listing.listing_plan_select == plan.id)
                    return false;

                return true;
            },
            setfeatureImage: function (data) {
                this.feature_image = data
            },
            handleFileUpload: function () {
                this.avatar = this.$refs.avatar.files[0];
            },

            mceSetter: function () {
                const vm = this;
                const iframes = document.querySelectorAll('.mce-tinymce iframe');
                for (let i = 0; i < iframes.length; i++) {
                    let iframe = iframes[i].contentWindow.document.querySelector('#tinymce');
                    let textarea = document.querySelector('#' + iframe.getAttribute('data-id'));
                    let key = textarea.getAttribute('data-name');

                    if (vm.attributes[key]) {
                        vm.attributes[key].value = iframe.innerHTML;
                    }
                }
            },

            buildData: function () {
                var vm = this;
                vm.formData = new FormData();
                if (vm.id)
                    vm.formData.append('id', vm.id);

                if (typeof vm.title != "undefined")
                    vm.formData.append('title', vm.title);

                vm.formData.append('listing_type', vm.listing_type);

                if (vm.listing_plan_select != null)
                    vm.formData.append('user_plan', vm.listing_plan_select);

                if (vm.feature_plan_select != null)
                    vm.formData.append('feature_plan', vm.feature_plan_select);


                for (let key in this.attributes) {
                    switch (vm.attributes[key].type) {
                        case 'category':
                            var name = this.attributes[key].name + '[]';
                            vm.formData.append(name, vm.attributes[key].value);
                            break;
                        case 'region':
                            var name = this.attributes[key].name + '[]';
                            vm.formData.append(name, vm.attributes[key].value);
                            break;
                        case 'date':
                            vm.formData.append('options[' + this.attributes[key].name + ']', moment(vm.attributes[key].value).format('DD/MM/YYYY'));
                            break;
                        case 'location':
                            if (this.attributes[key].value.address != undefined)
                                vm.formData.append('options[address]', this.attributes[key].value.address);

                            if (this.attributes[key].value.latitude != undefined)
                                vm.formData.append('options[latitude]', this.attributes[key].value.latitude);

                            if (this.attributes[key].value.longitude != undefined)
                                vm.formData.append('options[longitude]', this.attributes[key].value.longitude);

                            if (this.attributes[key].value.postal_code != undefined)
                                vm.formData.append('options[postal_code]', this.attributes[key].value.postal_code);
                            break;
                        case 'checkbox':
                            vm.attributes[key].value.forEach(function (item) {
                                var id = (vm.attributes[key].data && vm.attributes[key].data[item]) ? vm.attributes[key].data[item] : '';
                                var name = 'options[' + vm.attributes[key].name + '][' + id + ']';
                                vm.formData.append(name, item);
                            })
                            break;
                        case 'multiselect':
                            vm.attributes[key].value.forEach(function (item) {
                                var id = (vm.attributes[key].data && vm.attributes[key].data[item]) ? vm.attributes[key].data[item] : '';
                                var name = 'options[' + vm.attributes[key].name + '][' + id + ']';
                                vm.formData.append(name, item);
                            })
                            break;
                        case 'price':
                            var name = (this.attributes[key].data) ? 'options[price][value][' + this.attributes[key].data + ']' : 'options[price][value]';

                            if (this.attributes[key].value.genuine == undefined) this.attributes[key].value.genuine = '';
                            if (this.attributes[key].value.sale == undefined) this.attributes[key].value.sale = '';

                            vm.formData.append('options[price][meta][genuine]', this.attributes[key].value.genuine);
                            vm.formData.append('options[price][meta][sale]', this.attributes[key].value.sale);
                            vm.formData.append(name, (this.attributes[key].value.sale) ? this.attributes[key].value.sale : this.attributes[key].value.genuine);
                            break;
                        case 'gallery':
                            this.images = this.attributes[key];
                            break;
                        case 'file':
                            var name = this.attributes[key].name;
                            if (typeof vm.$refs[key] !== 'undefined' && typeof vm.$refs[key].files[0] !== 'undefined' ) {
                                vm.formData.append('options[' + name + '][]', null);
                                vm.formData.append(name + '[]', vm.$refs[key].files[0]);
                            } else if (this.attributes[key].data) {
                                vm.formData.append('options[' + name + '][' + this.attributes[key].data + ']', this.attributes[key].data_value);
                            }
                            break;
                        case 'accordion':
                            vm.formData.append("meta[" + key + "]", this.json_stringify(this.attributes[key].data));
                            break;
                        default:
                            var name = (this.attributes[key].data) ? 'options[' + key + '][' + this.attributes[key].data + ']' : 'options[' + key + ']';
                            vm.formData.append(name, this.attributes[key].value);
                            break
                    }
                }
            },
            send: function () {
                var vm = this;
                vm.message = null;
                vm.loading = true;

                vm.mceSetter();
                vm.buildData();

                this.$http.post(currentAjaxUrl + '?action=stm_listing_ajax', vm.formData).then(function (response) {
                    if (response.body['errors'])
                        vm.errors = response.body['errors'];

                    vm.message = response.body['message'];
                    vm.status = response.body['status'];

                    if (vm.status === 'success') {
                        const postId = response.body['listing_id'];
                        vm.response = response;
                        if (vm.images.value && vm.images.value.length > 0)
                            vm.sendFiles(postId);
                        else
                            vm.emptyFiles(postId);

                        $('.add-listing-navigate').find('.step').removeClass('active');
                        $('.add-listing-navigate').find('.step:last-child').addClass('active');

                    } else {
                        vm.loading = false;
                    }
                });
            },

            redirectAfterResponse(response) {
                const vm = this;
                if (vm.action === 'edit') {
                    vm.loading = false;
                    window.location.replace(this.return_url);
                    return;
                }
                this.step = 'last';
                if (response.body.listing_id !== "undefined")
                    vm.id = response.body.listing_id;
                vm.return_url = response.body.listing_url;
                vm.loading = false;
            },

            sendFiles(postId) {
                const vm = this;
                const store = [];
                Object.values(vm.images.value).forEach( (file, i) => {
                    const formData = new FormData()
                    const name = this.images.name

                    if ( i === 0 )
                formData.append('is_first', '1')

                if ( i === Object.values(vm.images.value).length - 1 )
                    formData.append('is_last', '1')

                formData.append('name', name)
                formData.append('post_id', postId)

                if (this.feature_image) {
                    formData.append('feature_image[attr]', this.feature_image.attr);
                    formData.append('feature_image[index]', this.feature_image.index);
                }

                if ( file.id ) {
                    formData.append('id', file.id)
                    formData.append('options[' + name + '][' + file.id + ']', file.value)
                } else {
                    formData.append('options[' + name + '][' + i + ']', null)
                    formData.append(name + '[]', file.file)
                }

                store.push(formData);
            })

                let i = 0;
                vm.one_by_one(store, i);
            },

            emptyFiles(postId) {
                const vm = this;
                const formData = new FormData()
                const name = this.images.name
                formData.append('post_id', postId)
                formData.append('name', name)
                formData.append('empty', '1')

                this.$http.post(currentAjaxUrl + '?action=stm_listing_file_ajax', formData).then(response => {
                    vm.redirectAfterResponse(vm.response);
            })
            },

            one_by_one(store, i) {
                const vm = this
                if ( typeof store[i] !== "undefined" ) {
                    const formData = store[i]
                    formData.append('index', i)

                    vm.$http.post(currentAjaxUrl + '?action=stm_listing_file_ajax', formData).then( (response) => {
                        const res = response.body
                        if ( res.status && typeof res.index !== "undefined" && typeof store[res.index] !== "undefined")
                    vm.one_by_one(store, res.index)
                else
                    vm.redirectAfterResponse(vm.response)
                })
                } else {
                    vm.redirectAfterResponse(vm.response);
                }
            },

            set_feature: function () {
                var vm = this;
                vm.message = null;
                vm.loading = true;
                this.$http.post("ulisting-listing/set-feature", {
                    listing_id: vm.id,
                    plan_id: vm.feature_plan_select
                }).then(function (response) {
                    vm.loading = false;
                    vm.message = response.body['message'];
                    if (response.body['errors'])
                        vm.errors = response.body['errors'];
                    if (response.body['success']) {
                    }
                });
            },

            accordion_toggle_open: function (item, open) {
                if (open)
                    item.is_open = open;
                else
                    item.is_open = !item.is_open

                if (item.is_open) {
                    setTimeout(function () {
                        tinymce.remove('#accordion_content_' + item.id);
                        tinymce.init({
                            selector: '#accordion_content_' + item.id,
                            plugins: 'image',
                            relative_urls: false,
                            remove_script_host: false,
                            convert_urls: true,
                            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
                            image_advtab: true,
                            content_css: [
                                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                                '//www.tiny.cloud/css/codepen.min.css'
                            ],
                            height: 300,
                            template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
                            template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
                            image_caption: true,
                            spellchecker_dialog: true,
                            spellchecker_whitelist: ['Ephox', 'Moxiecode'],
                            tinycomments_mode: 'embedded',
                            content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }',
                            init_instance_callback: function (editor) {
                                editor.on('Change', function (e) {
                                    item.content = tinymce.get('accordion_content_' + item.id).getContent({format: 'html'})
                                });
                            }
                        });

                        tinymce.get('accordion_content_' + item.id).on('keyup', function (e) {
                            item.content = tinymce.get('accordion_content_' + item.id).getContent({format: 'html'})
                        });
                    }, 1)
                }
            },
            remove: function (items, index) {
                items.splice(index, 1);
            },
            add_item_accordion: function (items) {
                items.push({
                    id: this.generateRandomId(),
                    title: "",
                    content: "",
                    is_open: false,
                    options: []
                })
            },
            json_stringify: function (data) {
                return JSON.stringify(data)
            },
            add_options: function (item) {
                item.options.push({
                    id: this.generateRandomId(),
                    key: "",
                    val: "",
                })
            },
        }
    });
}