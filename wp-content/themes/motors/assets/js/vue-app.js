"use strict";

var filterApp;
var recentNewsApp;
var stickyNewsApp;
var eventsApp;

var countdownInterval;

window.addEventListener('load', function () {

    filterApp = new Vue({
        el: '#filterResultBox',
        data: {
            posts : [],
            loadListings: false,
            filter: []
        },
        methods: {
            pushNewData: function (newData) {
                this.posts.push(newData);
                setTimeout(function () {
                    filterApp.loadListings = false;
                }, 300);
            }
        }
    });

    /*recentNewsApp = new Vue({
        el: '#stm_widget_recent_news',
        data: {
            posts : [],
            selectedCategory: 'all',
            showNews: false,
            showCats: false
        },
        created: function () {
            this.getPosts('all');
        },
        methods: {
            getPosts: function (category) {
                this.selectedCategory = category;
                this.showCats = false;

                var ajaxData = document.getElementById('stm_widget_recent_news').getAttribute('data-link');

                this.$http.get(stm_ajaxurl + ajaxData + '&category=' + category).then(function(response){
                    this.showNews = false;
                   this.posts = response.data;
                   setTimeout(function () {
                       recentNewsApp.showNews = true;
                   }, 200);
                });
            },
            showHideCategs: function() {
                this.showCats = (this.showCats) ? false : true;
            }
        }
    });

    stickyNewsApp = new Vue({
        el: '#features_posts_wrap',
        data: {
            posts: [],
            bigPost: {},
            miniPosts: [],
            hidenPosts: [],
            categs: [],
            slugs: [],
            postsPerPage: 4,
            selectedCategory: 'all',
            showHiden: false,
            loadCategory: true
        },
        created: function () {
            this.getPosts('all');
        },
        methods: {
            getPosts: function (category) {

                this.selectedCategory = category;

                var ajaxData = document.getElementById('features_posts_wrap').getAttribute('data-params');

                this.$http.get(stm_ajaxurl + ajaxData + '&category=' + category).then(function(response){
                    this.posts = response.data;
                    this.bigPost = {};
                    this.miniPosts = [];
                    this.hidenPosts = [];
                    this.runParsePosts();
                });
            },

            runParsePosts: function () {
                this.bigPost = this.posts[0];
                var hidePosition = document.getElementById('features_posts_wrap').getAttribute('data-hide-position');
                this.pushCategory(this.posts[0].category, this.posts[0].cat_slug);
                for(var q=1;q<this.posts.length;q++) {
                    this.pushCategory(this.posts[q].category, this.posts[q].cat_slug);
                    if(q <= hidePosition) this.miniPosts.push(this.posts[q]);
                    else this.hidenPosts.push(this.posts[q]);
                }

                this.loadCategory = false;
            },

            pushCategory: function (cat, slug) {
                if(cat != '' && this.loadCategory) {
                    this.categs.push(cat);
                    this.slugs.push(slug);
                }
            },

            showHide: function () {
                if(this.showHiden) {
                    this.showHiden = false;
                } else {
                    this.showHiden = true;
                }
            }
        }
    });

    eventsApp = new Vue({
        el: '#eventsMiddle',
        data: {
            posts: [],
            postPrev: {},
            showPreview: true,
            active: ''
        },
        created: function () {
            this.getEvents();
        },
        methods: {
            getEvents: function() {
                var ajaxData = document.getElementById('eventsMiddle').getAttribute('data-params');

                this.$http.get(stm_ajaxurl + ajaxData).then(function(response){
                    this.posts = response.data;
                    this.setPreviewPost(0);
                });
            },
            setPreviewPost: function (position) {
                this.showPreview = false;
                this.postPrev = this.posts[position];
                this.active = position;

                if(countdownInterval) clearInterval(countdownInterval);

                setTimeout(function() {
                    countdownInterval = setInterval(function () {
                        window.dateCountdown();
                    }, 1000);
                    eventsApp.showPreview = true;

                }, 200);


            }
        }
    });*/
});