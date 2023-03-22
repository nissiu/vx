<script type="text/javascript">
	window.vx_fields_loaded = el => {
		let publish = document.querySelector('#publish, .editor-post-publish-button__button');
		let is_block_editor = document.body.classList.contains('block-editor-page');
		let saved = false;
		window.addEventListener( 'message', e => {
			if ( e.data === 'create-post:submitted' ) {
				saved = true;
				publish.classList.remove('vx-disabled');
				publish.click();

				if ( is_block_editor ) {
					setTimeout( () => {
						el.contentWindow.location.reload();
						document.getElementById('vx-fields-wrapper').classList.remove('ts-saving');
					}, 500 );
				}
			}
		} );

		let listener = e => {
			if ( ! saved ) {
				e.preventDefault();
				el.contentWindow.document.querySelector('.ts-create-post .ts-save-changes').click();
				document.getElementById('vx-fields-wrapper').classList.add('ts-saving');
				publish.classList.add('vx-disabled');
				publish.removeEventListener( 'click', listener );
			}
		};

		publish.addEventListener( 'click', listener );

		el.style.height = el.contentWindow.document.body.offsetHeight+'px';
		let observer = new ResizeObserver( entries => el.style.height = entries[0].target.offsetHeight+'px' );
		observer.observe(el.contentWindow.document.body);
	};
</script>

<div id="vx-fields-wrapper">
	<iframe
		src="<?= add_query_arg( [
			'action' => 'admin.get_fields_form',
			'post_type' => $post->post_type->get_key(),
			'post_id' => $post->get_id(),
			'_wpnonce' => wp_create_nonce( 'vx_admin_edit_post' ),
		], home_url('/?vx=1') ) ?>"
		style="width: 100%; height: 0px; display: block;"
		frameborder="0"
		onload="vx_fields_loaded(this);"
	></iframe>
</div>
