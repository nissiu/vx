!(function (e) {
  "function" == typeof define && define.amd ? define("createPost", e) : e();
})(function () {
  "use strict";
  var s = {
      template: "#create-post-media-popup",
      props: {
        multiple: { type: Boolean, default: !0 },
        ignore: { type: Array, default: [] },
        customTarget: [Object, String],
        saveLabel: String,
      },
      emits: ["save", "blur", "open"],
      data() {
        return {
          files: [],
          selected: {},
          active: !1,
          loading: !0,
          has_more: !1,
          firstLoad: !0,
          search: {
            term: "",
            offset: 0,
            loading: !1,
            loading_more: !1,
            has_more: !1,
            list: null,
          },
        };
      },
      methods: {
        getStyle(e) {
          return e.type.startsWith("image/")
            ? `background-image: url('${e.preview}');`
            : "";
        },
        selectFile(e) {
          this.selected[e.id]
            ? delete this.selected[e.id]
            : (this.multiple || (this.selected = {}),
              (this.selected[e.id] = e));
        },
        loadMedia() {
          jQuery
            .get(Voxel_Config.ajax_url + "&action=list_media", {
              offset: this.files.length,
            })
            .always((e) => {
              (this.loading = !1),
                e.success
                  ? (this.files.push(...e.data), (this.has_more = e.has_more))
                  : Voxel.alert(
                      e.message || Voxel_Config.l10n.ajaxError,
                      "error"
                    );
            });
        },
        loadMore() {
          (this.loading = !0), this.loadMedia();
        },
        openLibrary() {
          this.$emit("open"),
            this.firstLoad && this.loadMedia(),
            (this.firstLoad = !1),
            (this.active = !this.active);
        },
        isImage(e) {
          return e.type.startsWith("image/");
        },
        save() {
          (this.active = !1),
            this.$emit("save", this.selected),
            (this.selected = {});
        },
        clear() {
          this.selected = {};
        },
        clientSearchFiles() {
          let t = this.search.term.trim().toLowerCase(),
            s = [],
            i = !1;
          this.files.forEach((e) => {
            i ||
              (-1 !== e.name.toLowerCase().indexOf(t) &&
                (s.push(e), (i = 10 <= s.length)));
          }),
            (this.search.list = s),
            (this.search.loading = !1),
            (this.search.has_more = !1),
            (this.search.loading_more = !1);
        },
        serverSearchFiles: Voxel.helpers.debounce((t, s = !1) => {
          jQuery
            .get(Voxel_Config.ajax_url + "&action=list_media", {
              offset: s ? t.search.list.length : 0,
              search: t.search.term.trim(),
            })
            .always((e) => {
              (t.search.loading = !1),
                (t.search.loading_more = !1),
                e.success
                  ? (s
                      ? t.search.list.push(...e.data)
                      : (t.search.list = e.data),
                    (t.search.has_more = e.has_more))
                  : Voxel.alert(
                      e.message || Voxel_Config.l10n.ajaxError,
                      "error"
                    );
            });
        }),
      },
      watch: {
        "search.term"() {
          this.search.term.trim() &&
            this.files &&
            ((this.search.loading = !0),
            !this.has_more || this.search.term.trim().length <= 2
              ? this.clientSearchFiles()
              : this.serverSearchFiles(this));
        },
      },
    },
    i = { template: "#create-post-title-field", props: { field: Object } },
    a = { template: "#create-post-text-field", props: { field: Object } },
    l = {
      template: "#create-post-texteditor-field",
      props: { field: Object },
      data() {
        return { rendered: !1 };
      },
      created() {
        var e;
        this.field.in_repeater &&
          "plain-text" !== this.field.props.editorType &&
          ((e = Voxel.helpers.sequentialId()),
          (this.field.props.editorId += e),
          (this.field.props.toolbarId += e),
          (this.field.props.editorConfig.textarea_name += e),
          (this.field.props.editorConfig.tinymce.fixed_toolbar_container += e));
      },
      mounted() {
        this.renderEditor();
      },
      methods: {
        renderEditor() {
          "plain-text" === this.field.props.editorType ||
            this.rendered ||
            this.field.step !== this.$root.currentStep.key ||
            ((this.$refs.editor.innerHTML = this.field.value),
            jQuery(() => {
              (this.field.props.editorConfig.tinymce.init_instance_callback = (
                e
              ) => {
                this.$nextTick(() => {
                  this.$refs.toolbar
                    .querySelector(".mce-flow-layout-item")
                    ?.addEventListener("touchstart", (e) =>
                      e.stopPropagation()
                    );
                }),
                  e.fire("focus");
              }),
                wp.oldEditor.initialize(
                  this.field.props.editorId,
                  this.field.props.editorConfig
                ),
                tinyMCE.editors[this.field.props.editorId].on("change", (e) => {
                  this.field.value = e.target.getContent();
                });
            }),
            (this.rendered = !0));
        },
      },
      watch: {
        "$root.currentStep"() {
          this.renderEditor();
        },
      },
    },
    r = { template: "#create-post-texteditor-field", extends: l },
    o = {
      template: "#create-post-number-field",
      props: { field: Object },
      created() {
        var e = parseFloat(this.field.value);
        isNaN(e) || (this.field.value = e);
      },
      methods: {
        increment() {
          "number" != typeof this.field.value
            ? this.setValue(this.field.props.min)
            : this.setValue(this.field.value + this.field.props.step);
        },
        decrement() {
          "number" != typeof this.field.value
            ? this.setValue(this.field.props.min)
            : this.setValue(this.field.value - this.field.props.step);
        },
        setValue(e) {
          "" === e || "number" != typeof e
            ? (this.field.value = null)
            : e < this.field.props.min
            ? (this.field.value = this.field.props.min)
            : e > this.field.props.max
            ? (this.field.value = this.field.props.max)
            : (this.field.value = Number(
                e.toFixed(this.field.props.precision)
              ));
        },
      },
    },
    d = { template: "#create-post-email-field", props: { field: Object } },
    n = { template: "#create-post-url-field", props: { field: Object } },
    h = {
      template: "#create-post-file-field",
      props: {
        field: Object,
        mediaTarget: [Object, String],
        index: { type: Number, default: null },
        sortable: { type: Boolean, default: !0 },
        showLibrary: { type: Boolean, default: !0 },
        previewImages: { type: Boolean, default: !0 },
      },
      data() {
        return { accepts: "", dragActive: !1 };
      },
      created() {
        null === this.field.value && (this.field.value = []),
          (this.accepts = Object.values(this.field.props.allowedTypes).join(
            ", "
          ));
      },
      mounted() {
        this.updatePreviews(),
          jQuery(this.$refs.input).on("change", (e) => {
            for (var t = 0; t < e.target.files.length; t++) {
              var s = e.target.files[t];
              this.pushFile(s);
            }
            (this.$refs.input.value = ""),
              this.updatePreviews(),
              this.$emit("files-added");
          }),
          jQuery(() => {
            var e = jQuery(this.$refs.fileList);
            this.sortable &&
              (e.sortable({
                items: "> .ts-file",
                helper: "clone",
                appendTo: this.$el,
                containment: "parent",
                tolerance: "intersect",
                revert: 150,
              }),
              e.on("sortupdate", () => {
                var s = [];
                e.find(".ts-file").each((e, t) => {
                  s.push(this.field.value[t.dataset.index]);
                }),
                  (this.field.value = s),
                  this.updatePreviews();
              })),
              e.find(".pick-file-input").on("click", (e) => {
                e.preventDefault(), this.$refs.input.click();
              });
          });
      },
      unmounted() {
        setTimeout(() => {
          Object.values(this.field.value).forEach((e) => {
            "new_upload" === e.source && URL.revokeObjectURL(e.preview);
          });
        }, 10),
          this.sortable && jQuery(this.$refs.fileList).sortable("destroy");
      },
      methods: {
        getStyle(e) {
          return e.type.startsWith("image/") && this.previewImages
            ? `background-image: url('${e.preview}');`
            : "";
        },
        updatePreviews() {
          var e = jQuery(this.$refs.fileList),
            i = [];
          this.field.value.forEach((e, t) => {
            var s = e.type.startsWith("image/") && this.previewImages,
              s = jQuery(`
					<div class="ts-file ${s ? "ts-file-img" : ""}" style="${this.getStyle(
                e
              )}" data-index="${t}">
						<div class="ts-file-info">
							<svg fill="#000000" width="52" height="52" version="1.1" id="lni_lni-cloud-upload" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
								<g>
									<path d="M34.3,27.3c-0.5-0.5-1.1-0.8-1.8-0.8c-0.7,0-1.3,0.3-1.8,0.8l-4.9,5.1c-0.7,0.7-0.7,1.8,0,2.5c0.7,0.7,1.8,0.7,2.5,0
										l2.5-2.6v9.5c0,1,0.8,1.8,1.8,1.8c1,0,1.8-0.8,1.8-1.8v-9.5l2.5,2.6c0.3,0.4,0.8,0.5,1.3,0.5c0.4,0,0.9-0.2,1.2-0.5
										c0.7-0.7,0.7-1.8,0-2.5L34.3,27.3z"/>
									<path d="M57.8,23.7c-2.7-2.9-6.6-4.9-10.6-5.6c-2.2-3.5-5.5-6.1-9.3-7.4c-1.7-0.6-3.7-1-5.8-1c-9.6,0-17.5,7.5-17.9,16.9
										C6.9,27.2,1.3,33.2,1.3,40.4c0,7.6,6.3,13.8,14.1,13.9c0,0,0,0,0,0h28.8c10.3,0,18.6-8.2,18.6-18.2C62.8,31.5,61,27.1,57.8,23.7z
										 M44.1,50.8H15.4c-6,0-10.6-4.6-10.6-10.4S9.4,30,15.4,30h0.5c1,0,1.8-0.8,1.8-1.8v-1.1c0-7.7,6.5-14,14.4-14
										c1.7,0,3.2,0.3,4.6,0.8c3.3,1.1,6.1,3.5,7.9,6.6c0.3,0.5,0.8,0.8,1.3,0.9c3.6,0.4,7,2,9.3,4.6c2.6,2.8,4,6.3,4,10
										C59.3,44.2,52.5,50.8,44.1,50.8z"/>
								</g>
						</svg><code></code>
						</div>
						<a href="#" class="ts-remove-file flexify"><svg fill="#000000" width="52" height="52" version="1.1" id="lni_lni-close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
							 y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
						<path d="M34.5,32L62.2,4.2c0.7-0.7,0.7-1.8,0-2.5c-0.7-0.7-1.8-0.7-2.5,0L32,29.5L4.2,1.8c-0.7-0.7-1.8-0.7-2.5,0
							c-0.7,0.7-0.7,1.8,0,2.5L29.5,32L1.8,59.8c-0.7,0.7-0.7,1.8,0,2.5c0.3,0.3,0.8,0.5,1.2,0.5s0.9-0.2,1.2-0.5L32,34.5l27.7,27.8
							c0.3,0.3,0.8,0.5,1.2,0.5c0.4,0,0.9-0.2,1.2-0.5c0.7-0.7,0.7-1.8,0-2.5L34.5,32z"/>
						</svg></a>
					</div>
				`);
            s.find("code").text(e.name),
              s.find("a").on("click", (e) => {
                e.preventDefault(),
                  this.field.value.splice(t, 1),
                  this.updatePreviews();
              }),
              i.push(s);
          }),
            e.find(".pick-file-input").siblings().remove(),
            e.append(i);
        },
        pushFile(e) {
          1 === this.field.props.maxCount && (this.field.value = []),
            this.field.value.push({
              source: "new_upload",
              name: e.name,
              type: e.type,
              preview: URL.createObjectURL(e),
              item: e,
            });
        },
        onDrop(e) {
          (this.dragActive = !1),
            e.dataTransfer.items
              ? [...e.dataTransfer.items].forEach((e) => {
                  "file" === e.kind && this.pushFile(e.getAsFile());
                })
              : [...e.dataTransfer.files].forEach((e) => {
                  this.pushFile(e);
                }),
            this.updatePreviews(),
            this.$emit("files-added");
        },
        onMediaPopupSave(e) {
          1 === this.field.props.maxCount && (this.field.value = []);
          var t = {};
          this.field.value.forEach((e) => {
            "existing" === e.source && (t[e.id] = !0);
          }),
            Object.values(e).forEach((e) => {
              t[e.id] || this.field.value.push(e);
            }),
            this.updatePreviews(),
            this.$emit("files-added");
        },
        onSubmit(t, s, e = null) {
          var i;
          (i =
            null !== e
              ? `files[${this.field.id}::row-${e}][]`
              : `files[${this.field.id}][]`),
            (t[this.field.key] = []),
            this.field.value.forEach((e) => {
              "new_upload" === e.source
                ? (s.append(i, e.item), t[this.field.key].push("uploaded_file"))
                : "existing" === e.source && t[this.field.key].push(e.id);
            });
        },
        async cacheToLocalStorage(a) {
          a[this.field.key] = [];
          let e = [];
          return (
            this.field.value.forEach((i) => {
              if ("existing" === i.source) a[this.field.key].push(i);
              else if ("new_upload" === i.source) {
                let t = {
                    source: "local_storage",
                    name: i.item.name,
                    type: i.item.type,
                    lastModified: i.item.lastModified,
                    dataUrl: null,
                  },
                  s = new FileReader();
                e.push(
                  new Promise((e) => {
                    (s.onload = () => {
                      (t.dataUrl = s.result), a[this.field.key].push(t), e();
                    }),
                      s.readAsDataURL(i.item);
                  })
                );
              }
            }),
            Promise.all(e)
          );
        },
        async applyFromLocalStorage(t) {
          if (Array.isArray(t) && t.length) {
            let e = [];
            return (
              t.forEach((s) => {
                e.push(
                  new Promise((t) => {
                    fetch(s.dataUrl)
                      .then((e) => e.blob())
                      .then((e) => {
                        this.pushFile(
                          new File([e], s.name, {
                            type: s.type,
                            lastModified: new Date(),
                          })
                        ),
                          t();
                      });
                  })
                );
              }),
              Promise.all(e).then(() =>
                this.$root.$nextTick(this.updatePreviews())
              ),
              Promise.all(e)
            );
          }
        },
      },
    },
    p = { template: "#create-post-phone-field", props: { field: Object } },
    u = {
      template: "#create-post-switcher-field",
      props: { field: Object, index: { type: Number, default: 0 } },
      data() {
        return { switcherId: `_switch-${this.field.id}:` + this.index };
      },
    },
    c = {
      template: "#create-post-location-field",
      props: { field: Object },
      data() {
        return { map: null };
      },
      mounted() {
        Voxel.Maps.await(() => {
          new Voxel.Maps.Autocomplete(
            this.$refs.addressInput,
            (e) => {
              e
                ? ((this.field.value.address = e.address),
                  (this.field.value.latitude = e.latlng.getLatitude()),
                  (this.field.value.longitude = e.latlng.getLongitude()),
                  this.map && this.map.fitBounds(e.viewport))
                : (this.field.value.address = this.$refs.addressInput.value);
            },
            this.$root.config.autocomplete
          ),
            this.field.value.map_picker &&
              this.$nextTick(() => this.setupMap());
        });
      },
      methods: {
        setupMap() {
          this.map ||
            Voxel.Maps.await(() => {
              (this.map = new Voxel.Maps.Map({
                el: this.$refs.mapDiv,
                zoom: this.field.props.default_zoom,
              })),
                (this.marker = new Voxel.Maps.Marker({
                  template: this.$refs.marker.innerHTML,
                }));
              var e = this.getMarkerPosition();
              e &&
                (this.map.setCenter(e),
                this.marker.setPosition(e),
                this.marker.setMap(this.map)),
                this.map.addListener("click", (e) => {
                  e = this.map.getClickPosition(e);
                  this.marker.getPosition() ||
                    (this.marker.setPosition(e), this.marker.setMap(this.map)),
                    (this.field.value.latitude = e.getLatitude()),
                    (this.field.value.longitude = e.getLongitude()),
                    Voxel.Maps.getGeocoder().geocode(
                      e.toGeocoderFormat(),
                      (e) => {
                        this.field.value.address = e.address;
                      }
                    );
                });
            });
        },
        getMarkerPosition() {
          return "number" != typeof this.field.value.latitude ||
            "number" != typeof this.field.value.longitude
            ? null
            : new Voxel.Maps.LatLng(
                this.field.value.latitude,
                this.field.value.longitude
              );
        },
        geolocate() {
          Voxel.Maps.getGeocoder().getUserLocation({
            fetchAddress: !0,
            receivedPosition: (e) => {
              (this.field.value.latitude = e.getLatitude()),
                (this.field.value.longitude = e.getLongitude());
            },
            receivedAddress: (e) => {
              (this.field.value.address = e.address),
                this.map && this.map.fitBounds(e.viewport);
            },
            positionFail: () =>
              Voxel.alert(Voxel_Config.l10n.positionFail, "error"),
            addressFail: () =>
              Voxel.alert(Voxel_Config.l10n.addressFail, "error"),
          });
        },
      },
      watch: {
        "field.value.map_picker"() {
          this.$nextTick(() => this.setupMap());
        },
        "field.value.latitude"() {
          this.marker?.setPosition(this.getMarkerPosition());
        },
        "field.value.longitude"() {
          this.marker?.setPosition(this.getMarkerPosition());
        },
      },
    },
    f = {
      template: "#create-post-work-hours-field",
      props: { field: Object },
      data() {
        return {};
      },
      methods: {
        addGroup() {
          this.field.value.push({ days: [], status: "hours", hours: [] });
        },
        removeGroup(e) {
          this.field.value.splice(this.field.value.indexOf(e), 1);
        },
        removeHours(e, t) {
          t.hours.splice(t.hours.indexOf(e), 1);
        },
        addHours(e) {
          e.hours.push({ from: "09:00", to: "17:00" });
        },
        displayDays(e) {
          return e
            .map((e) => this.field.props.weekdays[e])
            .filter(Boolean)
            .join(", ");
        },
        displayTime(e) {
          return new Date("2021-01-01 " + e)
            .toLocaleTimeString()
            .replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
        },
        id() {
          return this.field.id + "." + Object.values(arguments).join(".");
        },
        isChecked(e, t) {
          return t.includes(e);
        },
        check(e, t) {
          t.includes(e) ? t.splice(t.indexOf(e), 1) : t.push(e);
        },
        isDayAvailable(e, t) {
          return t.days.includes(e) || this.unusedDays.includes(e);
        },
      },
      computed: {
        unusedDays() {
          var t = [];
          return (
            this.field.value.forEach((e) => (t = t.concat(e.days))),
            Object.keys(this.field.props.weekdays).filter((e) => !t.includes(e))
          );
        },
      },
    };
  const m = {
    template: "#create-post-term-list",
    props: ["terms", "parent-term", "previous-list", "list-key"],
    data() {
      return {
        taxonomyField: Voxel.helpers.getParent(this, "taxonomy-field"),
        perPage: 50,
        page: 1,
      };
    },
    methods: {
      selectTerm(e) {
        e.children && e.children.length
          ? ((this.taxonomyField.slide_from = "right"),
            (this.taxonomyField.active_list = "terms_" + e.id))
          : this.taxonomyField.selectTerm(e);
      },
      goBack() {
        (this.taxonomyField.slide_from = "left"),
          (this.taxonomyField.active_list = this.previousList);
      },
      afterEnter(e, t) {
        setTimeout(
          () =>
            (e.closest(".min-scroll").scrollTop =
              this.taxonomyField.scrollPosition[t] || 0),
          100
        );
      },
      beforeLeave(e, t) {
        this.taxonomyField.scrollPosition[t] =
          e.closest(".min-scroll").scrollTop;
      },
    },
    computed: {
      termsWithChildren() {
        return this.terms.filter((e) => e.children && e.children.length);
      },
    },
  };
  var v = {
      template: "#create-post-taxonomy-field",
      name: "taxonomy-field",
      props: { field: Object, index: { type: Number, default: 0 } },
      data() {
        return {
          value: {},
          terms: this.field.props.terms,
          active_list: "toplevel",
          slide_from: "right",
          search: "",
          displayValue: "",
          scrollPosition: {},
          termCount: 0,
        };
      },
      created() {
        (this.value = this.field.props.selected),
          (this.displayValue = this._getDisplayValue());
        let s = (t, e) => {
          this.termCount++,
            (t.parentRef = e),
            t.children && t.children.forEach((e) => s(e, t));
        };
        this.terms.forEach((e) => s(e, null));
      },
      methods: {
        saveValue() {
          (this.field.value = this.isFilled() ? Object.keys(this.value) : null),
            (this.displayValue = this._getDisplayValue());
        },
        onSave() {
          this.saveValue(), this.$refs.formGroup.$refs.popup.$emit("blur");
        },
        onClear() {
          (this.value = {}), (this.search = ""), this.$refs.searchInput.focus();
        },
        isFilled() {
          return Object.keys(this.value).length;
        },
        _getDisplayValue() {
          return Object.values(this.value)
            .map((e) => e.label)
            .join(", ");
        },
        deselectChildren(e) {
          e.children &&
            e.children.forEach((e) => {
              delete this.value[e.slug], this.deselectChildren(e);
            });
        },
        selectTerm(t) {
          if (this.value[t.slug]) {
            delete this.value[t.slug], this.deselectChildren(t);
            let e = t.parentRef;
            for (; e; )
              e.children.some((e) => !!this.value[e.slug]) ||
                delete this.value[e.slug],
                (e = e.parentRef);
          } else {
            this.field.props.multiple ||
              Object.keys(this.value).forEach((e) => delete this.value[e]);
            let e = (this.value[t.slug] = t).parentRef;
            for (; e; ) e = (this.value[e.slug] = e).parentRef;
            this.field.props.multiple ||
              "inline" === this.field.props.display_as ||
              this.onSave();
          }
          "inline" === this.field.props.display_as && this.saveValue();
        },
      },
      computed: {
        searchResults() {
          var t, s;
          return (
            !!this.search.trim().length &&
            ((t = []),
            (s = (e) => {
              -1 !==
                e.label
                  .toLowerCase()
                  .indexOf(this.search.trim().toLowerCase()) && t.push(e),
                e.children && e.children.forEach(s);
            }),
            this.terms.forEach(s),
            t)
          );
        },
      },
    },
    y = {
      template: "#create-post-product-field",
      props: { field: Object },
      data() {
        return {
          interval: { unit: "month", count: 1 },
          calendar: {
            make_available_next: null,
            bookable_per_instance: 1,
            excluded_weekdays: {},
            excluded_days: [],
            timeslots: [{ days: [], slots: [] }],
          },
          state: {
            excluded_weekdays: {},
            excluded_weekday_indexes: [],
            weekdays_display_value: "",
            weekday_indexes: {
              sun: 0,
              mon: 1,
              tue: 2,
              wed: 3,
              thu: 4,
              fri: 5,
              sat: 6,
            },
            recurring_dates: [],
          },
          l10n: this.field.props.l10n,
          deliverables: {
            label: this.field.props.deliverables.label,
            id: this.field.id + ".deliverables",
            key: "deliverables",
            value: this.field.value.deliverables,
            props: {
              allowedTypes: this.field.props.deliverables.allowed_file_types,
              maxCount: this.field.props.deliverables.max_count,
            },
          },
        };
      },
      created() {
        var e;
        null !== this.field.value &&
          ((e = this.field.value.calendar),
          (this.calendar = {
            make_available_next: e.make_available_next,
            bookable_per_instance: e.bookable_per_instance || 1,
            excluded_weekdays: e.excluded_weekdays || {},
            excluded_days: e.excluded_days || [],
            timeslots: e.timeslots || [{ days: [], slots: [] }],
          }),
          Array.isArray(e.excluded_weekdays) &&
            (e.excluded_weekdays.forEach(
              (e) => (this.state.excluded_weekdays[e] = !0)
            ),
            this.saveWeekdayExclusions()),
          (e = this.field.value.interval),
          (this.interval = { unit: e.unit || "month", count: e.count || 1 }));
      },
      mounted() {
        this.$nextTick(() => this.getRecurrences());
      },
      methods: {
        saveWeekdayExclusions() {
          (this.calendar.excluded_weekdays = jQuery.extend(
            {},
            this.state.excluded_weekdays
          )),
            (this.state.excluded_weekday_indexes = []),
            Object.keys(this.calendar.excluded_weekdays).forEach((e) => {
              this.state.excluded_weekday_indexes.push(
                this.state.weekday_indexes[e]
              );
            });
          var t = [];
          Object.keys(this.field.props.weekdays).forEach((e) => {
            this.calendar.excluded_weekdays[e] &&
              t.push(this.field.props.weekdays[e]);
          }),
            (this.state.weekdays_display_value = t.join(", ")),
            this.$refs.datePicker?.refresh(),
            this.$refs.weekdayExclusions?.$refs?.popup?.$emit("blur");
        },
        clearWeekdayExclusions() {
          (this.state.excluded_weekdays = {}), this.saveWeekdayExclusions();
        },
        toggleWeekdayExclusion(e) {
          this.state.excluded_weekdays[e]
            ? delete this.state.excluded_weekdays[e]
            : (this.state.excluded_weekdays[e] = !0);
        },
        onSubmit(e, t) {
          var s = {};
          this.field.props.additions?.map((e) => {
            s[e.key] = e.values;
          }),
            (e[this.field.key] = {
              enabled: this.field.value.enabled,
              base_price: this.field.value.base_price,
              price_id: this.field.value.price_id,
              interval: this.interval,
              calendar: {
                make_available_next: this.calendar.make_available_next,
                bookable_per_instance: this.calendar.bookable_per_instance,
                excluded_weekdays: Object.keys(this.calendar.excluded_weekdays),
                excluded_days: this.calendar.excluded_days,
                timeslots: this.calendar.timeslots,
              },
              additions: s,
              notes: this.field.value.notes,
              notes_enabled: this.field.value.notes_enabled,
            }),
            this.$refs.deliverables?.onSubmit(e[this.field.key], t);
        },
        getRecurrences() {
          if ("recurring-date" === this.field.props.calendar_type) {
            let e = this.field.props.recurring_date_field,
              s = this.$root.$refs["field:" + e];
            var t;
            s &&
              s.getUpcoming &&
              (this.$watch(
                () => this.$root.fields[e].value,
                (t = () => {
                  let t = new Date();
                  t.setDate(t.getDate() + this.calendar.make_available_next),
                    (this.state.recurring_dates = s
                      .getUpcoming(20)
                      .filter((e) => e.start.getTime() <= t.getTime()));
                }),
                { deep: !0 }
              ),
              this.$watch(() => this.calendar.make_available_next, t, {
                deep: !0,
              }),
              t());
          }
        },
        formatRecurrence(e) {
          var t = Voxel.helpers.dateFormat(e.start),
            e = Voxel.helpers.dateFormat(e.end);
          return t === e ? t : t + " - " + e;
        },
      },
    },
    g = {
      template:
        '<div class="ts-calendar-wrapper ts-availability-calendar"><input type="hidden" ref="input"></div>',
      data() {
        return {
          picker: null,
          calendar: this.$parent.calendar,
          today: new Date(),
        };
      },
      mounted() {
        this.picker = new Pikaday({
          field: this.$refs.input,
          container: this.$el,
          bound: !1,
          firstDay: 1,
          keyboardInput: !1,
          onSelect: (e) => {
            var t = Voxel.helpers.dateFormatYmd(e);
            this.calendar.excluded_days.includes(t)
              ? (this.calendar.excluded_days =
                  this.calendar.excluded_days.filter((e) => e !== t))
              : this.calendar.excluded_days.push(t);
          },
          selectDayFn: (e) =>
            this.calendar.excluded_days.includes(
              Voxel.helpers.dateFormatYmd(e)
            ),
          disableDayFn: (e) => {
            if (
              e < this.today ||
              this.$parent.state.excluded_weekday_indexes.includes(e.getDay())
            )
              return !0;
          },
        });
      },
      unmounted() {
        this.picker.destroy();
      },
      methods: {
        refresh() {
          this.picker.draw();
        },
      },
    },
    x = {
      template: "#create-post-product-timeslots",
      data() {
        return {
          timeslots: this.$parent.calendar.timeslots,
          field: this.$parent.field,
          create: { from: "09:00", to: "09:30" },
          generate: { from: "09:00", to: "17:00", length: 30 },
        };
      },
      created() {
        this.timeslots.forEach((e) => (e._collapsed = !0)),
          this.updateWeekdayExclusions();
      },
      methods: {
        isDayUsed(e, t) {
          return -1 < t.days.indexOf(e);
        },
        addSlotGroup() {
          this.timeslots.push({ days: [], slots: [], _collapsed: !1 });
        },
        isDayAvailable(e, t) {
          return this.isDayUsed(e, t) || -1 !== this.unusedDays.indexOf(e);
        },
        addSlot(e, t) {
          var s = e.slots.find(
            (e) => e.from === this.create.from && e.to === this.create.to
          );
          this.create.from &&
            this.create.to &&
            !s &&
            e.slots.push({ from: this.create.from, to: this.create.to }),
            this.closeSlotPopup(t);
        },
        closeSlotPopup(e) {
          this.$refs[this.groupKey(e, "add")].$refs.popup.$emit("blur");
        },
        removeSlot(t, e) {
          e.slots = e.slots.filter((e) => e !== t);
        },
        removeGroup(t) {
          this.timeslots = this.timeslots.filter((e) => e !== t);
        },
        saveDays(e) {
          this.$refs[this.groupKey(e)].$refs.popup.$emit("blur");
        },
        clearDays(e) {
          e.days = [];
        },
        toggleDay(t, e) {
          this.isDayUsed(t, e)
            ? (e.days = e.days.filter((e) => e !== t))
            : e.days.push(t);
        },
        groupKey(e, t = "") {
          t = t.length ? "." + t : "";
          return this.field.key + ".slots." + e + t;
        },
        daysLabel(e, t) {
          return (
            e.days.map((e) => this.field.props.weekdays[e]).join(", ") || t
          );
        },
        displaySlot(e) {
          return e.from && e.to
            ? new Date("2021-01-01 " + e.from)
                .toLocaleTimeString()
                .replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") +
                " — " +
                new Date("2021-01-01 " + e.to)
                  .toLocaleTimeString()
                  .replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3")
            : "";
        },
        generateSlots(e, t) {
          var s = this.generate.from.split(":"),
            i = this.generate.to.split(":"),
            a = this.generate.length,
            l = parseInt(s[0], 10),
            s = parseInt(s[1], 10),
            r = parseInt(i[0], 10),
            i = parseInt(i[1], 10);
          if (!(isNaN(l) || isNaN(s) || isNaN(r) || isNaN(i) || a < 5)) {
            var o = [],
              d = 60 * l + s,
              n = 60 * r + i;
            for (n <= d && (n += 1440); d < n && !(n < d + a); ) {
              var h = { hour: Math.floor(d / 60), minute: d % 60 },
                p = { hour: Math.floor((d + a) / 60), minute: (d + a) % 60 };
              24 <= h.hour && (h.hour -= 24),
                24 <= p.hour && (p.hour -= 24),
                o.push({
                  from: [
                    h.hour.toString().padStart(2, "0"),
                    h.minute.toString().padStart(2, "0"),
                  ].join(":"),
                  to: [
                    p.hour.toString().padStart(2, "0"),
                    p.minute.toString().padStart(2, "0"),
                  ].join(":"),
                }),
                (d += a);
            }
            (e.slots = o), this.closeGeneratePopup(t);
          }
        },
        closeGeneratePopup(e) {
          this.$refs[this.groupKey(e, "generate")].$refs.popup.$emit("blur");
        },
        updateWeekdayExclusions() {
          (this.$parent.state.excluded_weekdays = this.unusedDays.reduce(
            (e, t) => ((e[t] = !0), e),
            {}
          )),
            this.$parent.saveWeekdayExclusions();
        },
      },
      computed: {
        unusedDays() {
          var t = [];
          return (
            this.timeslots.forEach((e) => (t = t.concat(e.days))),
            Object.keys(this.field.props.weekdays).filter((e) => !t.includes(e))
          );
        },
      },
      watch: {
        unusedDays() {
          this.updateWeekdayExclusions();
        },
      },
    },
    w = { template: "#create-post-ui-image-field", props: { field: Object } },
    _ = { template: "#create-post-ui-heading-field", props: { field: Object } },
    b = {
      template: "#create-post-repeater-field",
      props: { field: Object },
      data() {
        return {
          rows: this.field.props.rows,
          sortedRows: this.field.props.rows.slice(),
        };
      },
      created() {
        this.rows.forEach((e) => {
          this.$root.setupConditions(e),
            (e["meta:state"].id = Voxel.helpers.sequentialId());
        });
      },
      mounted() {
        this.$nextTick(() => {
          jQuery(this.$refs.list).sortable({
            items: "> .ts-field-repeater",
            containment: "parent",
            tolerance: "pointer",
            helper: "clone",
            handle: ".ts-repeater-head",
          }),
            jQuery(this.$refs.list).on("sortupdate", (e) => {
              e.stopPropagation();
              let s = this.sortedRows;
              (this.sortedRows = []),
                jQuery(this.$refs.list)
                  .find("> .ts-field-repeater")
                  .each((e, t) => {
                    this.sortedRows.push(s[t.dataset.index]),
                      (t.dataset.index = e);
                  });
            });
        }),
          this.$nextTick(() => this.rows.forEach((e) => this.getRowLabel(e)));
      },
      unmounted() {
        jQuery(this.$refs.list).sortable("destroy");
      },
      methods: {
        addRow() {
          var e = Vue.reactive(jQuery.extend(!0, {}, this.field.props.fields));
          (e["meta:state"].id = Voxel.helpers.sequentialId()),
            this.rows.push(e),
            this.sortedRows.push(e),
            this.$root.setupConditions(e),
            this.$nextTick(() => this.getRowLabel(e));
        },
        deleteRow(t) {
          (this.rows = this.rows.filter((e) => e !== t)),
            (this.sortedRows = this.sortedRows.filter((e) => e !== t));
        },
        onSubmit(e, l) {
          (e[this.field.key] = []),
            this.sortedRows.forEach((s, i) => {
              var a = {};
              Object.values(s).forEach((e) => {
                var t = this.$refs[`row#${s["meta:state"].id}:` + e.key];
                "meta:state" !== e.key &&
                  ("meta:additions" === e.key
                    ? (a["meta:additions"] = e)
                    : !e.is_ui &&
                      this.$root.conditionsPass(e) &&
                      (t && "function" == typeof t.onSubmit
                        ? t.onSubmit(a, l, i)
                        : null !== e.value && (a[e.key] = e.value)));
              }),
                e[this.field.key].push(a);
            });
        },
        getRowLabel(s) {
          let i = s["meta:state"],
            a = (e) => (i.label = e);
          var e = this.field.props.row_label;
          let l = this.field.props.l10n.item;
          if ("addition:label" === e)
            this.$watch(
              () => s["meta:additions"]?.label,
              () => a(s["meta:additions"]?.label || l)
            ),
              a(s["meta:additions"]?.label || l);
          else if ("addition:price" === e)
            this.$watch(
              () => s["meta:additions"]?.price,
              () => a(s["meta:additions"]?.price || l)
            ),
              a(s["meta:additions"]?.price || l);
          else {
            let e = s[this.field.props.row_label],
              t = this.$refs[`row#${i.id}:` + e?.key];
            if (!e || !t) return l;
            "taxonomy" === e.type
              ? (t.$watch("displayValue", () => a(t.displayValue || l)),
                a(t.displayValue || l))
              : "select" === e.type
              ? (this.$watch(
                  () => e.value,
                  () => a(e.props.choices[e.value]?.label || l)
                ),
                a(e.props.choices[e.value]?.label || l))
              : (this.$watch(
                  () => e.value,
                  () => a(e.value?.toString() || l)
                ),
                a(e.value?.toString() || l));
          }
        },
      },
    },
    k = {
      template: "#create-post-timezone-field",
      props: { field: Object },
      data() {
        return { search: "" };
      },
      methods: {
        onSave() {
          this.$refs.formGroup.blur();
        },
        onClear() {
          (this.field.value = null), (this.search = "");
        },
      },
      computed: {
        choices() {
          return this.search.trim().length
            ? this.field.props.list.filter(
                (e) =>
                  -1 !==
                  e.toLowerCase().indexOf(this.search.trim().toLowerCase())
              )
            : this.field.props.list;
        },
      },
    },
    e = {
      template: "#recurring-date-range-picker",
      props: { date: Object },
      emits: ["save"],
      data() {
        return {
          picker: null,
          activePicker: "start",
          value: {
            start: this.date.startDate
              ? new Date(this.date.startDate + "T00:00:00")
              : null,
            end: this.date.endDate
              ? new Date(this.date.endDate + "T00:00:00")
              : null,
          },
        };
      },
      mounted() {
        (this.picker = new Pikaday({
          field: this.$refs.input,
          container: this.$refs.calendar,
          bound: !1,
          firstDay: 1,
          keyboardInput: !1,
          numberOfMonths: 2,
          defaultDate: this.value.start,
          startRange: this.value.start,
          endRange: this.value.end,
          theme: "pika-range",
          onSelect: (e) => {
            "start" === this.activePicker
              ? (this.setStartDate(e), (this.activePicker = "end"))
              : (this.setEndDate(e),
                (this.activePicker = "start"),
                (this.date.startDate = Voxel.helpers.dateFormatYmd(
                  this.value.start
                )),
                (this.date.endDate = Voxel.helpers.dateFormatYmd(
                  this.value.end
                )),
                this.$emit("save")),
              this.refresh();
          },
          selectDayFn: (e) =>
            !(
              !this.value.start ||
              e.toDateString() !== this.value.start.toDateString()
            ) ||
            !(
              !this.value.end ||
              e.toDateString() !== this.value.end.toDateString()
            ) ||
            void 0,
          disableDayFn: (e) => {
            if (
              "end" === this.activePicker &&
              this.value.start &&
              e < this.value.start
            )
              return !0;
          },
        })),
          this.setStartDate(this.value.start),
          this.setEndDate(this.value.end),
          this.refresh();
      },
      unmounted() {
        setTimeout(() => this.picker.destroy(), 200);
      },
      methods: {
        setStartDate(e) {
          (this.value.start = e),
            this.picker.setStartRange(e),
            this.value.end &&
              this.value.start > this.value.end &&
              this.setEndDate(null);
        },
        setEndDate(e) {
          (this.value.end = e), this.picker.setEndRange(e);
        },
        refresh() {
          this.picker.draw();
        },
        reset() {
          this.setStartDate(null),
            this.setEndDate(null),
            this.refresh(),
            (this.activePicker = "start");
        },
      },
      computed: {
        startLabel() {
          return this.value.start
            ? Voxel.helpers.dateFormat(this.value.start)
            : "From";
        },
        endLabel() {
          return this.value.end
            ? Voxel.helpers.dateFormat(this.value.end)
            : "To";
        },
      },
      watch: {
        activePicker() {
          this.refresh();
        },
      },
    },
    D = {
      template: "#create-post-recurring-date-field",
      props: { field: Object, index: { type: Number, default: 0 } },
      components: {
        datePicker: {
          template: "#recurring-date-picker",
          props: ["modelValue", "minDate"],
          emits: ["save", "update:modelValue"],
          data() {
            return { picker: null };
          },
          mounted() {
            this.picker = new Pikaday({
              field: this.$refs.input,
              container: this.$refs.calendar,
              bound: !1,
              firstDay: 1,
              keyboardInput: !1,
              defaultDate: this.modelValue ? new Date(this.modelValue) : null,
              onSelect: (e) => {
                this.$emit("update:modelValue", Voxel.helpers.dateFormatYmd(e)),
                  this.$emit("save");
              },
              selectDayFn: (e) =>
                this.modelValue &&
                this.modelValue === Voxel.helpers.dateFormatYmd(e),
            });
          },
          unmounted() {
            setTimeout(() => this.picker.destroy(), 200);
          },
          watch: {
            modelValue() {
              this.picker.draw();
            },
          },
        },
        dateRangePicker: e,
      },
      methods: {
        add() {
          this.field.value.push({
            multiday: !1,
            startDate: null,
            startTime: "00:00",
            endDate: null,
            endTime: "00:00",
            repeat: !1,
            frequency: 1,
            unit: "week",
            until: null,
          });
        },
        remove(e) {
          this.field.value.splice(this.field.value.indexOf(e), 1);
        },
        id() {
          return (
            this.field.id +
            ":" +
            this.index +
            "." +
            Object.values(arguments).join(".")
          );
        },
        clearDate(e) {
          (e.startDate = null),
            (e.startTime = null),
            (e.endDate = null),
            (e.endTime = null),
            this.$refs.rangePicker?.reset();
        },
        getStartDate(e) {
          var t = e.startTime || "00:00:00",
            t = new Date(e.startDate + " " + t);
          return e.startDate && isFinite(t) ? t : null;
        },
        getEndDate(e) {
          var t = e.endTime || "00:00:00",
            t = new Date(e.endDate + " " + t);
          return e.endDate && isFinite(t) ? t : null;
        },
        getUntilDate(e) {
          var t = new Date(e.until);
          return e.until && isFinite(t) ? t : null;
        },
        format(e) {
          return Voxel.helpers.dateTimeFormat(e);
        },
        formatDate(e) {
          return Voxel.helpers.dateFormat(e);
        },
        getUpcoming(t = 10) {
          let o = [],
            d = new Date();
          return (
            this.field.value.forEach((e) => {
              let i = this.getStartDate(e),
                a = this.getEndDate(e);
              var l = this.getUntilDate(e);
              let r = t;
              if (
                i &&
                a &&
                (i >= d &&
                  (o.push({ start: new Date(i), end: new Date(a) }), r--),
                e.repeat) &&
                1 <= e.frequency &&
                l > i &&
                l > d
              ) {
                let t = e.frequency,
                  s = e.unit;
                if (
                  ("week" === e.unit
                    ? ((s = "day"), (t *= 7))
                    : "year" === e.unit && ((s = "month"), (t *= 12)),
                  i < d)
                ) {
                  for (; i < d; )
                    "day" === s
                      ? ((i = new Date(i.setDate(i.getDate() + t))),
                        (a = new Date(a.setDate(a.getDate() + t))))
                      : "month" === s &&
                        ((i = new Date(i.setMonth(i.getMonth() + t))),
                        (a = new Date(a.setMonth(a.getMonth() + t))));
                  o.push({ start: new Date(i), end: new Date(a) }), r--;
                }
                for (
                  let e = 0;
                  e < r &&
                  ("day" === s
                    ? ((i = new Date(i.setDate(i.getDate() + t))),
                      (a = new Date(a.setDate(a.getDate() + t))))
                    : "month" === s &&
                      ((i = new Date(i.setMonth(i.getMonth() + t))),
                      (a = new Date(a.setMonth(a.getMonth() + t)))),
                  !(i > l));
                  e++
                )
                  o.push({ start: new Date(i), end: new Date(a) });
              }
            }),
            o.sort((e, t) => e.start.getTime() - t.start.getTime()).splice(0, t)
          );
        },
      },
    },
    $ = {
      template: "#create-post-date-field",
      components: {
        datePicker: {
          template: "#create-post-date-field-picker",
          props: { field: Object, parent: Object },
          data() {
            return { picker: null };
          },
          mounted() {
            this.picker = new Pikaday({
              field: this.$refs.input,
              container: this.$refs.calendar,
              bound: !1,
              firstDay: 1,
              keyboardInput: !1,
              defaultDate: this.parent.date,
              onSelect: (e) => {
                (this.parent.date = e), this.parent.onSave();
              },
              selectDayFn: (e) =>
                this.parent.date &&
                this.parent.date.toDateString() === e.toDateString(),
            });
          },
          unmounted() {
            setTimeout(() => this.picker.destroy(), 200);
          },
          methods: {
            reset() {
              (this.parent.date = null), this.picker.draw();
            },
          },
        },
      },
      props: { field: Object, index: { type: Number, default: 0 } },
      data() {
        return {
          date: this.field.value.date,
          time: this.field.value.time,
          displayValue: "",
        };
      },
      created() {
        "string" == typeof this.date &&
          ((this.date = new Date(this.date + "T00:00:00")),
          (this.displayValue = this.getDisplayValue()));
      },
      methods: {
        saveValue() {
          (this.field.value.date = this.isFilled()
            ? Voxel.helpers.dateFormatYmd(this.date)
            : null),
            (this.displayValue = this.getDisplayValue());
        },
        onSave() {
          this.saveValue(), this.$refs.formGroup.blur();
        },
        onClear() {
          this.$refs.picker.reset();
        },
        isFilled() {
          return this.date && isFinite(this.date);
        },
        getDisplayValue() {
          return this.date ? Voxel.helpers.dateFormat(this.date) : "";
        },
      },
    },
    V = {
      template: "#create-post-select-field",
      props: { field: Object, index: { type: Number, default: 0 } },
      data() {
        return { value: this.field.value };
      },
      created() {
        null === this.field.value ||
          this.field.props.choices[this.field.value] ||
          ((this.field.value = null), (this.value = null));
      },
      methods: {
        saveValue() {
          this.field.value = this.isFilled() ? this.value : null;
        },
        onSave() {
          this.saveValue(), this.$refs.formGroup.blur();
        },
        onClear() {
          this.value = null;
        },
        isFilled() {
          return null !== this.value && this.field.props.choices[this.value];
        },
      },
    },
    S = { template: "#create-post-color-field", props: { field: Object } },
    j = {
      template: "#create-post-post-relation-field",
      props: { field: Object, index: { type: Number, default: 0 } },
      data() {
        return {
          posts: { loading: !1, has_more: !1, list: null },
          value: this.field.props.selected,
          displayValue: "",
          search: {
            term: "",
            offset: 0,
            loading: !1,
            loading_more: !1,
            has_more: !1,
            list: null,
          },
        };
      },
      created() {
        this.displayValue = this._getDisplayValue();
      },
      methods: {
        onOpen() {
          null === this.posts.list &&
            ((this.posts.list = []), this.loadPosts());
        },
        loadPosts() {
          (this.posts.loading = !0),
            jQuery
              .get(
                Voxel_Config.ajax_url +
                  "&action=create_post.relations.get_posts",
                {
                  post_types: this.field.props.post_types.join(","),
                  offset: this.posts.list.length,
                  post_id: this.$root.post?.id,
                  exclude: this.$root.post?.id,
                }
              )
              .always((e) => {
                (this.posts.loading = !1),
                  e.success
                    ? (this.posts.list.push(...e.data),
                      (this.posts.has_more = e.has_more))
                    : Voxel.alert(
                        e.message || Voxel_Config.l10n.ajaxError,
                        "error"
                      );
              });
        },
        saveValue() {
          (this.field.value = this.isFilled() ? Object.keys(this.value) : null),
            (this.displayValue = this._getDisplayValue());
        },
        onSave() {
          this.saveValue(), this.$refs.formGroup.blur();
        },
        onClear() {
          Object.keys(this.value).forEach((e) => delete this.value[e]);
        },
        isFilled() {
          return Object.keys(this.value).length;
        },
        _getDisplayValue() {
          var e = Object.values(this.value);
          let t = "";
          return (
            e[0] && (t += e[0].title),
            1 < e.length && (t += " +" + (e.length - 1)),
            t
          );
        },
        selectPost(e) {
          this.value[e.id]
            ? delete this.value[e.id]
            : (this.field.props.multiple ||
                Object.keys(this.value).forEach((e) => delete this.value[e]),
              (this.value[e.id] = e),
              this.field.props.multiple || this.onSave());
        },
        clientSearchPosts() {
          let t = this.search.term.trim().toLowerCase(),
            s = [],
            i = !1;
          this.posts.list.forEach((e) => {
            i ||
              (-1 !== e.title.toLowerCase().indexOf(t) &&
                (s.push(e), (i = 10 <= s.length)));
          }),
            (this.search.list = s),
            (this.search.loading = !1),
            (this.search.has_more = !1),
            (this.search.loading_more = !1);
        },
        serverSearchPosts: Voxel.helpers.debounce((t, s = !1) => {
          jQuery
            .get(
              Voxel_Config.ajax_url + "&action=create_post.relations.get_posts",
              {
                post_types: t.field.props.post_types.join(","),
                offset: s ? t.search.list.length : 0,
                post_id: t.$root.post?.id,
                exclude: t.$root.post?.id,
                search: t.search.term.trim(),
              }
            )
            .always((e) => {
              (t.search.loading = !1),
                (t.search.loading_more = !1),
                e.success
                  ? (s
                      ? t.search.list.push(...e.data)
                      : (t.search.list = e.data),
                    (t.search.has_more = e.has_more))
                  : Voxel.alert(
                      e.message || Voxel_Config.l10n.ajaxError,
                      "error"
                    );
            });
        }),
      },
      watch: {
        "search.term"() {
          this.search.term.trim() &&
            this.posts.list &&
            ((this.search.loading = !0),
            !this.posts.has_more || this.search.term.trim().length <= 2
              ? this.clientSearchPosts()
              : this.serverSearchPosts(this));
        },
      },
    };
  (window.Voxel.conditionHandlers = {
    "text:equals": (e, t) => t === e.value,
    "text:not_equals": (e, t) => t !== e.value,
    "text:empty": (e, t) => !t?.trim()?.length,
    "text:not_empty": (e, t) => !!t?.trim()?.length,
    "text:contains": (e, t) => t?.match(new RegExp(e.value, "i")),
    "taxonomy:contains": (e, t) => Array.isArray(t) && t.includes(e.value),
    "taxonomy:not_contains": (e, t) =>
      !(Array.isArray(t) && t.includes(e.value)),
    "taxonomy:empty": (e, t) => !Array.isArray(t) || !t.length,
    "taxonomy:not_empty": (e, t) => Array.isArray(t) && t.length,
    "switcher:checked": (e, t) => !!t,
    "switcher:unchecked": (e, t) => !t,
    "number:empty": (e, t) => isNaN(parseFloat(t)),
    "number:equals": (e, t) => parseFloat(t) === parseFloat(e.value),
    "number:gt": (e, t) => parseFloat(t) > parseFloat(e.value),
    "number:gte": (e, t) => parseFloat(t) >= parseFloat(e.value),
    "number:lt": (e, t) => parseFloat(t) < parseFloat(e.value),
    "number:lte": (e, t) => parseFloat(t) <= parseFloat(e.value),
    "number:not_empty": (e, t) => !isNaN(parseFloat(t)),
    "number:not_equals": (e, t) => parseFloat(t) !== parseFloat(e.value),
    "file:empty": (e, t) => !Array.isArray(t) || !t.length,
    "file:not_empty": (e, t) => Array.isArray(t) && t.length,
    "date:empty": (e, t) =>
      !isFinite(new Date(t.date + " " + (t.time || "00:00:00"))),
    "date:gt": (e, t) => {
      (t = new Date(t.date + " " + (t.time || "00:00:00"))),
        (e = new Date(e.value));
      return !(!isFinite(t) || !isFinite(e)) && e < t;
    },
    "date:lt": (e, t) => {
      (t = new Date(t.date + " " + (t.time || "00:00:00"))),
        (e = new Date(e.value));
      return !(!isFinite(t) || !isFinite(e)) && t < e;
    },
    "date:not_empty": (e, t) =>
      isFinite(new Date(t.date + " " + (t.time || "00:00:00"))),
  }),
    (window.render_create_post = () => {
      Array.from(document.querySelectorAll(".ts-create-post")).forEach((e) => {
        var t;
        e.__vue_app__ ||
          ((t = ((e) => {
            let a = JSON.parse(
              e
                .closest(".elementor-widget-container")
                .querySelector(".vxconfig").innerHTML
            );
            return Vue.createApp({
              el: e,
              mixins: [Voxel.mixins.base],
              data() {
                return {
                  config: a,
                  activePopup: null,
                  fields: {},
                  steps: [],
                  post_type: {},
                  post: null,
                  step_index: null,
                  submission: {
                    status: null,
                    processing: !1,
                    done: !1,
                    viewLink: null,
                    editLink: null,
                    message: null,
                  },
                };
              },
              created() {
                ((window.CP = this).fields = a.fields),
                  (this.steps = a.steps),
                  (this.post_type = a.post_type),
                  (this.post = a.post || null),
                  this.setupConditions(this.fields);
                let t = Voxel.getSearchParam("step");
                var e = this.activeSteps.findIndex((e) => e === t);
                t && 0 < e ? this.setStep(e) : this.setStep(0),
                  a.errors.forEach((e) => console.log(e));
              },
              mounted() {
                e.classList.toggle("ts-ready");
              },
              methods: {
                setupConditions(s) {
                  Object.values(s).forEach((r) => {
                    r.conditions &&
                      r.conditions.forEach((e) => {
                        e.forEach((i) => {
                          var e = i.source.split("."),
                            t = e[0];
                          let a = e[1] || null,
                            l = s[t];
                          if (l) {
                            let e = l.value;
                            null !== a && (e = e ? e[a] : null),
                              this.evaluateCondition(i, e, r, l),
                              this.$watch(
                                () =>
                                  null !== a
                                    ? l.value
                                      ? l.value[a]
                                      : null
                                    : l.value,
                                (e, t) => {
                                  let s = l.value;
                                  null !== a && (s = s ? s[a] : null),
                                    this.evaluateCondition(i, s, r, l);
                                },
                                { deep: !0 }
                              );
                          }
                        });
                      });
                  });
                },
                evaluateCondition(e, t, s, i) {
                  var a = Voxel.conditionHandlers[e.type];
                  a && (e._passes = a(e, t, s, i));
                },
                conditionsPass(e) {
                  var s;
                  return (
                    !e.conditions ||
                    ((s = !1),
                    e.conditions.forEach((e) => {
                      var t;
                      e.length &&
                        ((t = !0),
                        e.forEach((e) => {
                          e._passes || (t = !1);
                        }),
                        t) &&
                        (s = !0);
                    }),
                    s)
                  );
                },
                prevStep() {
                  0 < this.step_index && this.setStep(this.step_index - 1),
                    this.scrollIntoView();
                },
                nextStep() {
                  this.step_index < this.steps.length - 1 &&
                    this.setStep(this.step_index + 1),
                    this.scrollIntoView();
                },
                scrollIntoView() {
                  e.closest(".elementor-element").getBoundingClientRect().top <
                    0 && window.scrollTo({ top: 0, left: 0 });
                },
                setStep(e) {
                  (this.step_index = e),
                    0 < this.step_index && this.currentStep
                      ? Voxel.setSearchParam("step", this.currentStep.key)
                      : Voxel.deleteSearchParam("step");
                },
                submit() {
                  this.submission.processing = !0;
                  var s = new FormData(),
                    i = {},
                    e =
                      (Object.values(this.fields).forEach((e) => {
                        var t = this.$refs["field:" + e.key];
                        !e.is_ui &&
                          this.conditionsPass(e) &&
                          (t && "function" == typeof t.onSubmit
                            ? t.onSubmit(i, s)
                            : null !== e.value && (i[e.key] = e.value));
                      }),
                      s.append("postdata", JSON.stringify(i)),
                      jQuery.param({
                        action: a.is_admin_mode
                          ? "create_post__admin"
                          : "create_post",
                        post_type: this.post_type.key,
                        post_id: this.post?.id,
                        admin_mode: a.is_admin_mode ? a.admin_mode_nonce : null,
                      }));
                  jQuery
                    .post({
                      url: Voxel_Config.ajax_url + "&" + e,
                      data: s,
                      contentType: !1,
                      processData: !1,
                    })
                    .always((e) => {
                      (this.submission.processing = !1),
                        a.is_admin_mode
                          ? window.parent.postMessage(
                              "create-post:submitted",
                              "*"
                            )
                          : e.success
                          ? ((this.submission.done = !0),
                            (this.submission.viewLink = e.view_link),
                            (this.submission.editLink = e.edit_link),
                            (this.submission.message = e.message),
                            (this.submission.status = e.status),
                            this.scrollIntoView())
                          : e.errors
                          ? Voxel.alert(e.errors.join("<br>"), "error")
                          : Voxel.alert(
                              e.message || Voxel_Config.l10n.ajaxError,
                              "error"
                            );
                    });
                },
                toggleRow(e) {
                  e.target
                    .closest(".ts-field-repeater")
                    .classList.toggle("collapsed");
                },
              },
              computed: {
                currentStep() {
                  return this.fields[this.activeSteps[this.step_index]];
                },
                activeSteps() {
                  return this.steps.filter((e) =>
                    this.conditionsPass(this.fields[e])
                  );
                },
              },
            });
          })(e)).component("form-popup", Voxel.components.popup),
          t.component("form-group", Voxel.components.formGroup),
          t.component("media-popup", s),
          t.component("term-list", m),
          t.component("field-title", i),
          t.component("field-text", a),
          t.component("field-texteditor", l),
          t.component("field-description", r),
          t.component("field-number", o),
          t.component("field-email", d),
          t.component("field-url", n),
          t.component("field-file", h),
          t.component("field-image", h),
          t.component("field-profile-avatar", h),
          t.component("field-profile-name", a),
          t.component("field-taxonomy", v),
          t.component("field-phone", p),
          t.component("field-switcher", u),
          t.component("field-location", c),
          t.component("field-work-hours", f),
          t.component("field-product", y),
          t.component("field-product-calendar", g),
          t.component("field-product-timeslots", x),
          t.component("field-ui-image", w),
          t.component("field-ui-heading", _),
          t.component("field-repeater", b),
          t.component("field-timezone", k),
          t.component("field-recurring-date", D),
          t.component("field-date", $),
          t.component("field-select", V),
          t.component("field-color", S),
          t.component("field-post-relation", j),
          t.mount(e));
      });
    }),
    window.render_create_post(),
    jQuery(document).on("voxel:markup-update", window.render_create_post);
});
