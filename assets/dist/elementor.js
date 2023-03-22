!function(e){"function"==typeof define&&define.amd?define("elementor",e):e()}(function(){"use strict";elementor.modules.controls.Voxel_Post_Select=elementor.modules.controls.BaseData.extend({onReady(){let t=this.model.attributes.post_type,l=this.$el.find("input");var e=this.$el.find(".clear-value");let i=this.$el.find(".search-results"),s=this.$el.find(".value-wrap"),a=this.$el.find(".current-value"),n=a.attr("href");var o,r=this.getControlValue();r&&(o=VX_Post_Select_Cache[r]?`<span>: ${VX_Post_Select_Cache[r]}</span>`:"",a.html(`<b>#${r}</b>`+o),a.attr("href",n.replace(":id",r)),s.show()),l.on("input",Voxel_Backend.helpers.debounce(e=>{e=e.currentTarget.value.trim();e.length?(i.addClass("vx-disabled"),jQuery.get(Voxel_Config.ajax_url,{action:"general.search_posts",search:e,post_types:t.join(",")}).always(e=>{if(i.removeClass("vx-disabled hide"),e.success&&e.results.length){let t=[];e.results.forEach(e=>{t.push(`
						<div class="search-result">
							<a href="#" data-id="${e.id}">
								<b>#${e.id}: </b>
								<span>${e.title}</span>
							</a>
						</div>`)}),i.html(t.join(""))}else i.html("<p>No results found.</p>")})):i.addClass("hide")})),i.on("click","a",e=>{e.preventDefault();var t=e.currentTarget.dataset.id,e=e.currentTarget.querySelector("span").innerText;this.setValue(t),this.applySavedValue(),a.html(`<b>#${t}</b><span>: ${e}</span>`),a.attr("href",n.replace(":id",t)),s.show(),l.val(""),i.addClass("hide")}),e.on("click",e=>{e.preventDefault(),console.log(s),this.setValue(""),this.applySavedValue(),s.hide()})}}),elementor.addControlView("voxel-relation",elementor.modules.controls.BaseData.extend({onReady(){var a=this.model.get("vx_target"),n=this.model.get("vx_group"),o="right"===this.model.get("vx_side")?"right":"left",r="right"==o?"left":"right",e=elementor.$previewContents.find(".elementor-widget."+a),d=this.$el.find(".vx-relation-list"),t=a.replace("elementor-widget-",""),c=Vue.capitalize(Vue.camelize(t.replace("ts-",""))),h=this.options.container.id;elementor.config.voxel.relations[n]||(elementor.config.voxel.relations[n]=[]),e.each((e,t)=>{var l,i,s;t.parentElement.closest("."+a)||(l=t.dataset.id,i=!!elementor.config.voxel.relations[n].find(e=>e[o]===h&&e[r]===l),(s=$(`
				<div class="relation-item ${i?"selected":""}">
					<span>&lt;${c} <code>#${l}</code>&gt;</span>
					<i class="las la-eye view-icon"></i>
					<i class="las la-check check-icon"></i>
				</div>
			`)).on("mouseenter",()=>t.classList.add("voxel-highlight-element")),s.on("mouseleave",()=>t.classList.remove("voxel-highlight-element")),s.on("click",".view-icon",e=>{e.stopPropagation(),Voxel_Backend.helpers.isAnyPartOfElementInViewport(t)||t.scrollIntoView({behavior:"smooth",block:"nearest",inline:"start"})}),s.on("click",()=>{s.toggleClass("selected").siblings().removeClass("selected"),this.setValue(null),elementor.config.voxel.relations[n]=elementor.config.voxel.relations[n].filter(e=>e[o]!==h),s.hasClass("selected")&&(elementor.config.voxel.relations[n]=elementor.config.voxel.relations[n].filter(e=>e[r]!==l),elementor.config.voxel.relations[n].push({left:"left"==o?h:l,right:"left"==o?l:h})),d.addClass("vx-disabled"),jQuery.post(Voxel_Config.ajax_url,{action:"elementor.save_temporary_config",document_id:elementor.config.document.id,voxel:JSON.stringify(window.voxel_prepare_page_settings())}).always(e=>{d.removeClass("vx-disabled"),e.success&&[h,l].forEach(e=>{var t=elementor.$previewContents.find(".elementor-element-"+e).parents("[data-id]").map((e,t)=>t.dataset.id).toArray().reverse();t.push(e);let l=elementor.elements._byId[t[0]];for(t.shift();t.length&&l;)l=l.attributes.elements._byId[t[0]],t.shift();l&&"widget"===l.attributes.elType&&l.renderRemoteServer()})})}),d.append(s))}),d.find(".relation-item").length||d.html(`<div class="relation-item">No &lt;${c}&gt; widgets found.</div>`)}})),elementor.addControlView("voxel-visibility",elementor.modules.controls.BaseData.extend({onReady(){this._updatePreview(),this.$el.find(".vx-visibility-edit a").on("click",()=>{DTags.editVisibility(this.getControlValue(),e=>{this.setValue(e.length?e:null),this.applySavedValue(),this._updatePreview()})})},_updatePreview(){var e=this.getControlValue();this.$el.find(".vx-visibility-rules").html(DTags.formatRulesAsHTML(e))}}));var i={text:"BaseData",textarea:"BaseData",number:"Number",wysiwyg:"Wysiwyg",date_time:"Date_time",url:"Url",media:"Media",gallery:"Gallery",icons:"Icons",code:"Code","voxel-post-select":"Voxel_Post_Select"};Object.keys(i).forEach(e=>{var t,l=elementor.modules.controls[i[e]];l?(t={onReady:l.prototype.onReady||(()=>{}),onBeforeDestroy:l.prototype.onBeforeDestroy||(()=>{})},elementor.addControlView(e,l.extend({onReady(){t.onReady.call(this),this.$el.prepend('<a href="#" class="enable-tags"><span>Enable Voxel tags</span></a>'),this.$el.find(".elementor-control-input-wrapper").before(`
				<div class="edit-voxel-tags" style="display: none;">
					<div class="tags-content"></div>
					<div class="dbuttons">
						<a href="#" class="edit-tags">Edit tags</a>
						<a href="#" class="disable-tags">Disable tags</a>
					</div>
				</div>
			`),DTags.isDynamicString(this._getDynamicValue())&&this._enableTags(),this.$el.on("click",".enable-tags",()=>{this._enableTags(),this._editTags()}),this.$el.on("click",".edit-tags",this._editTags.bind(this)),this.$el.on("click",".disable-tags",this._disableTags.bind(this))},onBeforeDestroy(){t.onBeforeDestroy.call(this),this.$el.off("click",".enable-tags",this._enableTags.bind(this)),this.$el.off("click",".edit-tags",this._editTags.bind(this)),this.$el.off("click",".disable-tags",this._disableTags.bind(this))},_getDynamicValue(){return this.getControlValue()},_setDynamicValue(e){this.setValue(e)},_editTags(){DTags.edit(DTags.getDynamicString(this._getDynamicValue()),e=>{this._setDynamicValue(`@tags()${e}@endtags()`),this.applySavedValue(),this.$el.find(".edit-voxel-tags .tags-content").html(DTags.formatAsHTML(e?.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;")))})},_enableTags(){var e=DTags.getDynamicString(this._getDynamicValue());this._setDynamicValue(`@tags()${e}@endtags()`),this.applySavedValue(),this.$el.addClass("voxel-tags-active"),this.$el.find(".enable-tags").hide(),this.$el.find(".edit-voxel-tags .tags-content").html(DTags.formatAsHTML(e?.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;"))),this.$el.find(".edit-voxel-tags").show()},_disableTags(){confirm("Are you sure?")&&(this.$el.removeClass("voxel-tags-active"),this.$el.find(".enable-tags").show(),this.$el.find(".edit-voxel-tags").hide(),this._setDynamicValue(""),this.applySavedValue())}}))):console.warn(`Control module for "${e}" does not exist.`)}),elementor.modules.controls.Color.prototype.applySavedValue=function(){var e,t=this.getCurrentValue();this.colorPicker?t?(e=this.colorPicker.picker._parseLocalColor(t),this.colorPicker.picker.setHSVA(...e.values||[],!1)):this.colorPicker.picker._clearColor(!0):this.initPicker(),this.$el.toggleClass("e-control-color--no-value",!t)},elementor.modules.controls.Url.prototype._getDynamicValue=function(){return this.getControlValue().url},elementor.modules.controls.Url.prototype._setDynamicValue=function(e){return this.setValue("url",e)},elementor.modules.controls.Media.prototype._getDynamicValue=function(){return this.getControlValue().id},elementor.modules.controls.Media.prototype._setDynamicValue=function(e){return this.setValue("id",e)},elementor.modules.controls.Gallery.prototype._getDynamicValue=function(){return this.getControlValue()[0]?.id||[]},elementor.modules.controls.Gallery.prototype._setDynamicValue=function(e){return this.setValue([{id:e,url:" "}])},elementor.modules.controls.Icons.prototype._getDynamicValue=function(){return this.getControlValue().value},elementor.modules.controls.Icons.prototype._setDynamicValue=function(e){return this.setValue({value:e,library:" "})},elementor.modules.controls.Icons.prototype.applySavedValue=function(){var e=this.getControlValue(),t=this.model.get("skin"),l="inline"===t?this.ui.inlineDisplayedIcon:this.ui.previewPlaceholder,i=this.model.get("disable_initial_active_state"),s=this.model.get("default");let a=e.value,n=e.library;return this.isMigrationAllowed()||a||!this.getValueToMigrate()||(a=this.getControlValue(),n=""),"media"===t&&this.ui.controlMedia.toggleClass("elementor-media-empty",!a),("inline"===t&&!i||n)&&this.markChecked(n),a?"svg"===n&&"inline"!==t?elementor.helpers.fetchInlineSvg(a.url,e=>{new RegExp("<html|<head|<body","im").test(e)||this.ui.previewPlaceholder.html(e)}):("media"!==t&&"svg"===n||(e='<i class="'+a+'"></i>',l.html(e)),void this.enqueueIconFonts(n)):"inline"===t?void this.setDefaultIconLibraryLabel(s,l):void this.ui.previewPlaceholder.html("")},elementor.modules.controls.Select2.prototype.handleLockedOptions=function(){const t=this.model.get("lockedOptions");t&&this.ui.select.on("select2:unselecting",e=>{t.includes(e.params.args.data.id)&&e.preventDefault()});var l=this.ui.select,i=l.next(".select2-container").first("ul.select2-selection__rendered");i.on("click touchstart",e=>{$(e.target).hasClass("select2-selection__choice__remove")&&e.stopImmediatePropagation()}),i.sortable({placeholder:"ui-state-highlight",forcePlaceholderSize:!0,items:"li:not(.select2-search__field)",tolerance:"pointer",containment:"parent"}),i.on("sortupdate",e=>{e.stopImmediatePropagation(),$(i.find(".select2-selection__choice").get().reverse()).each((e,t)=>{var t=$(t).find("._s2-value").data("value");t&&(t=l.find('option[value="'+t+'"]')[0],l.prepend(t))}),this.ui.select.trigger("change")}),i.on("sortstop",e=>e.stopImmediatePropagation())},elementor.modules.controls.Select2.prototype.getSelect2Options=function(){return $.extend(this.getSelect2DefaultOptions(),this.model.get("select2options"),{templateSelection:e=>$('<span class="_s2-value"></span>').data("value",e.id).text(e.text)})},window.voxel_handle_tags=l=>(Object.keys(l).forEach(e=>{var t;"string"==typeof l[e]&&DTags.isDynamicString(l[e])&&(t=DTags.getDynamicString(l[e]),l[e]=`<span class="dtags-elementor" dtags>${DTags.formatAsText(t)}</span>`)}),l),window.voxel_prepare_page_settings=()=>{function i(e){let l=[];return l.push(e.parent().find("> .elementor-element").index(e)),e.parents("[data-id]").each((e,t)=>{l.push(jQuery(t).parent().find("> .elementor-element").index(t))}),l.reverse().join(".")}let t=elementor.config.voxel.relations,l=(Object.keys(t).forEach(e=>{t[e].filter(e=>{var t=elementor.$previewContents.find(".elementor-element-"+e.left),l=elementor.$previewContents.find(".elementor-element-"+e.right);return!(!t.length||!l.length||(e.leftPath=i(t),e.rightPath=i(l),0))})}),{});return elementor.$previewContents.find(".elementor-widget-ts-template-tabs").each((e,t)=>{l[t.dataset.id]=i(jQuery(t))}),{relations:t,template_tabs:l}},elementor.hooks.addFilter("elements/widget/behaviors",e=>(e.InlineEditing.behaviorClass.prototype.onInlineEditingClick=function(e){var t=this,l=jQuery(e.currentTarget);l.find("[dtags]").length||setTimeout(function(){t.startEditing(l)},30)},e)),$e.components.get("document/save").on("save",e=>{function i(e){let l=[];return l.push(e.parent().find("> .elementor-element").index(e)),e.parents("[data-id]").each((e,t)=>{l.push(jQuery(t).parent().find("> .elementor-element").index(t))}),l.reverse().join(".")}let t=elementor.config.voxel.relations,l=(Object.keys(t).forEach(e=>{t[e].filter(e=>{var t=elementor.$previewContents.find(".elementor-element-"+e.left),l=elementor.$previewContents.find(".elementor-element-"+e.right);return!(!t.length||!l.length||(e.leftPath=i(t),e.rightPath=i(l),0))})}),{});elementor.$previewContents.find(".elementor-widget-ts-template-tabs").each((e,t)=>{l[t.dataset.id]=i(jQuery(t))}),elementorCommon.ajax.addRequestConstant("voxel",JSON.stringify(window.voxel_prepare_page_settings()))});{let t=Voxel_Elementor_Config;t&&t.is_preview_card&&(document.body.classList.add("vx-editing-preview-card"),elementor.once("preview:loaded",()=>{var e=elementor.$preview.contents();e.find("body").addClass("vx-viewport-card"),e.find(".elementor.elementor-"+t.header_id).hide(),e.find(".elementor.elementor-"+t.footer_id).hide(),e.find("#query-monitor-main, .grecaptcha-badge").hide()}))}});