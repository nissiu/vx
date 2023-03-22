<script type="text/html" id="orders-display-note">
	<template v-if="note.type === 'comment'">
		<a :href="note.author.link" v-html="note.author.avatar"></a>
		<div class="ts-status">
			<div class="ts-status-head flexify">
				<a href="#" class="ts_status-author">{{ note.author.name }}</a>
				<div>
					<span><?= _x( 'posted a comment', 'single order', 'voxel' ) ?></span>
					<span class="ts-status-time">{{ note.time }}</span>
				</div>
			</div>
			<div class="ts-status-body">
				<p v-if="note.message" v-html="note.message"></p>
				<div v-if="note.files" class="ts-status-attachments">
					<ul class="simplify-ul">
						<li v-for="file in note.files">
							<a :href="file.url" target="_blank">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
								<span>{{ file.name }}</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</template>
	<template v-else-if="note.type === 'author.delivered'">
		<a :href="note.author.link" v-html="note.author.avatar"></a>
		<div class="ts-status">
			<div class="ts-status-head flexify">
				<a href="#" class="ts_status-author">{{ note.author.name }}</a>
				<div>
					<span><?= _x( 'Delivered files', 'single order', 'voxel' ) ?></span>
					<span class="ts-status-time">{{ note.time }}</span>
				</div>
			</div>
			<div class="ts-status-body">
				<p v-if="note.message" v-html="note.message"></p>
				<div v-if="note.files" class="ts-status-attachments">
					<ul class="simplify-ul">
						<li v-for="file in note.files">
							<a
								:href="file.url"
								target="_blank"
								:class="{'vx-disabled': (file.limit && file.count >= file.limit) || !file.downloadable}"
								@click="order.order.role.is_customer && !order.order.role.is_admin && file.count++"
							>
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
								<span>
									{{ file.name }}
									<span v-if="file.limit" class="ts-download-limit">
										{{ file.count }}/{{ file.limit }} <?= _x( 'downloads', 'single order', 'voxel' ) ?>
									</span>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</template>
	<template v-else>
		<div class="ts-system-ico">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_system_ico') ) ?: \Voxel\svg( 'bot.svg' ) ?>
		</div>
		<div class="ts-status">
			<div class="ts-status-head flexify system-note">
				<p class="ts_status-author">{{ note.message }}</p>
				<div>
					<span class="ts-status-time">{{ note.time }}</span>
				</div>
			</div>
		</div>
	</template>
</script>
