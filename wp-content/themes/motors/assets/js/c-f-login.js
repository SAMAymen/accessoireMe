new VueW3CValid({ el: '#stm-c-f-listing-login' });
new Vue({
    el:'#stm-c-f-listing-login',
    data:{
        loading: false,
        login: '',
        password: '',
        message: null,
        remember: 0,
        status: '',
        errors: []
    },
    methods:{
        logInCF: function() {
            var vm = this;
            vm.loading = true;
            vm.message = null;
            var data = {
                'login' : vm.login,
                'password' : vm.password,
                'remember' : vm.remember
            };
            vm.errors = [];
            this.$http.post(currentAjaxUrl + '?action=stm_listing_login', data).then(function(response){
                vm.loading = false;
                vm.message = response.body['message'];
                vm.status  = response.body['status'];
                if(response.body['errors'])
                    vm.errors = response.body['errors'];
                if(vm.status == 'success')
                    location.reload();
            });
        }
    }
});