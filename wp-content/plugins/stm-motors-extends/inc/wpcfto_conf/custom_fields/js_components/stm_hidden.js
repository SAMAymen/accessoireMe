Vue.component('wpcfto_stm_hidden', {
    props: ['fields', 'field_label', 'field_name', 'field_id', 'field_value'],
    data: function () {
        return {
            value : '',
        }
    },
    template: `
        <div style="display: none;">
            <input type="hidden"
                v-bind:name="field_name"
                v-bind:placeholder="fields.placeholder"
                v-bind:id="field_id"
                v-model="value"
            />
        </div>
    `,
    mounted: function () {
        this.value = this.field_value;

    },
    methods: {},
    watch: {
        value: function (value) {
            this.$emit('wpcfto-get-value', value);
        }
    }
});