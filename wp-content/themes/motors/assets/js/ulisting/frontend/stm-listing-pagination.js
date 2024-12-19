Vue.component('stm-listing-pagination', {
	data: function(){
		return {
		}
	},
	created() {
		if(+( this.getParameterByName('current_page') )) {
			this.page = +( this.getParameterByName('current_page') );
		}
	},
	methods: {
		clickCallback: function(page) {
			this.$emit('pagination-update', page);
		},

		getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, '\\$&');
			let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}
	},
	components:{
		"paginate":VuejsPaginate
	},
	props: {
		url:{
			default:""
		},
		page:{
			default: 0
		},
		page_count:{
			default: 0
		},

		next: {
			default: ''
		},
		prev: {
			default: ''
		}
	},
	template: `
			<div>
				<paginate
				  v-if="page_count > 1"  
				  v-model="page"
				  :page-range="8"
				  :pageCount="page_count"
				  :containerClass="'pagination'"
				  :clickHandler="clickCallback"
				  :prev-text="prev"
				  :next-text="next" >
				</paginate>
			</div>
        `,
});