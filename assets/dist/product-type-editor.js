!function(e){"function"==typeof define&&define.amd?define("productTypeEditor",e):e()}(function(){"use strict";var i={template:"#product-type-additions-template",data(){return{active:null}},methods:{toggleActive(e){return this.active=e===this.active?null:e},insertAddition(e){var t=$.extend(!0,{},e);if(!e.singular){for(var i=1,s=t.type+"-addition",a=s;this.$root.getAdditionByKey(a);)a=s+"-"+ ++i;t.key=a}this.$root.config.additions.push(t),t.__first_edit=!0,this.active=t},deleteAddition(t){this.$root.config.additions=this.$root.config.additions.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},s={template:"#product-type-addition-modal-template",props:["addition"],mounted(){this.addition.__first_edit&&this.$refs.keyInput.enable()},methods:{save(){this.$parent.active=null,delete this.addition.__first_edit}}},a={template:"#product-type-fields-template",data(){return{field_types:this.$root.options.field_types,active:null}},methods:{addField(e){for(var e=$.extend(!0,{},e),t=1,i=e.type,s=i;this.$root.config.fields.find(e=>e.key===s);)s=i+"-"+ ++t;e.key=s,this.$root.config.fields.push(e),e.__first_edit=!0,this.active=e},deleteField(t){this.$root.config.fields=this.$root.config.fields.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},o={template:"#product-type-field-modal-template",props:["field"],mounted(){this.field.__first_edit&&this.$refs.keyInput.enable()},methods:{save(){this.$parent.active=null,delete this.field.__first_edit}}},d={template:"#product-type-rate-list-template",props:["modelValue","mode","source"],data(){return{loading:!1,open:!1,rates:null,first_item:null,is_last_page:!1}},methods:{show(){this.open=!0,this.loading=!0,null===this.rates&&jQuery.get(Voxel_Config.ajax_url,{action:this.source,mode:this.mode},e=>{this.loading=!1,this.is_last_page=!e.has_more,this.rates=e.rates,this.first_item=this.rates?.[0]?.id})},toggle(e){var t=this.modelValue.indexOf(e.id);-1<t?this.modelValue.splice(t,1):this.modelValue.push(e.id)},isSelected(e){return-1<this.modelValue.indexOf(e.id)},remove(e){e=this.modelValue.indexOf(e);-1<e&&this.modelValue.splice(e,1)},prev(){this.loading=!0;var e=this.rates[0].id;jQuery.get(Voxel_Config.ajax_url,{action:this.source,mode:this.mode,ending_before:e},e=>{this.loading=!1,this.has_more=e.has_more,e.rates.length&&(this.rates=e.rates,this.is_last_page=!1)})},next(){this.loading=!0;var e=this.rates[this.rates.length-1].id;jQuery.get(Voxel_Config.ajax_url,{action:this.source,mode:this.mode,starting_after:e},e=>{this.loading=!1,this.has_more=e.has_more,e.rates.length&&(this.rates=e.rates,this.is_last_page=!e.has_more)})}}},r={template:`
		<div class="ts-icon-picker ts-icon-picker-vue">
			<div class="icon-preview" v-html="previewMarkup" @click.prevent="openLibrary" :title="modelValue"></div>
			<div class="basic-ul">
				<li><a href="#" @click.prevent="openLibrary" class="ts-button ts-faded">Choose Icon</a></li>
				<li><a href="#" @click.prevent="uploadSVG" class="ts-button ts-faded">Upload SVG</a></li>
				<li><a href="#" @click.prevent="clear" class="ts-button ts-faded icon-only"><i class="lar la-trash-alt icon-sm"></i></a></li>
			</div>
		</div>
	`,props:["modelValue"],data(){return{previewMarkup:""}},created(){this.preview(this.modelValue)},methods:{preview(e){Voxel_Icon_Picker.getIconPreview(e,e=>this.previewMarkup=e)},openLibrary(){Voxel_Icon_Picker.edit(this.modelValue,e=>{this.setValue(e.library+":"+e.value)})},uploadSVG(){Voxel_Icon_Picker.getSVG(e=>{this.setValue("svg:"+e.id)})},clear(){this.setValue("")},setValue(e){this.preview(e),this.$emit("update:modelValue",e)}}},l={template:"#post-type-select-field-choices",props:{field:Object},data(){return{active:null}},methods:{add(){var e={value:"",label:"",icon:""};this.field.choices.push(e),this.active=e},remove(t){this.field.choices=this.field.choices.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},n={template:"#product-type-tags-template",components:{"tag-modal":{template:"#product-type-tags-modal-template",props:["tag"],mounted(){this.tag.__first_edit&&this.$refs.keyInput.enable()},methods:{save(){this.$parent.active=null,delete this.tag.__first_edit}},watch:{"tag.is_default"(){this.tag.is_default&&this.$root.config.tags.forEach(e=>{e.key!==this.tag.key&&(e.is_default=!1)})}}}},data(){return{active:null}},methods:{toggleActive(e){return this.active=e===this.active?null:e},insertTag(){var e=jQuery.extend(!0,{},this.$root.options.tag_props);e.key="order-tag-"+(this.$root.config.tags.length+1),this.$root.config.tags.push(e),e.__first_edit=!0,this.active=e},deleteTag(t){this.$root.config.tags=this.$root.config.tags.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}};jQuery(e=>{var t;document.getElementById("voxel-edit-product-type")&&(window.$=jQuery,(t=Vue.createApp({data(){return{tab:"general",subtab:"base",config:Product_Type_Config,submit_config:"",options:Product_Type_Options}},created(){var e;window.PTE=this,window.location.hash&&(e=window.location.hash.replace("#","").split("."),this.setTab(e[0],e[1]))},methods:{setTab(e,t=""){this.tab=e,this.subtab=t,window.location.hash=t?e+"."+t:e},prepareSubmission(){this.submit_config=JSON.stringify(this.config)},getAdditionByKey(t){return this.config.additions.find(e=>e.key===t)}}})).component("field-key",Voxel_Backend.components.Field_Key),t.component("color-picker",Voxel_Backend.components.Color_Picker),t.component("draggable",vuedraggable),t.component("product-additions",i),t.component("addition-modal",s),t.component("information-fields",a),t.component("field-modal",o),t.component("icon-picker",r),t.component("rate-list",d),t.component("select-field-choices",l),t.component("order-tags",n),t.mount("#voxel-edit-product-type"))})});
