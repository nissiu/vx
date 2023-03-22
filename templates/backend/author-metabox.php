<div id="vx-post-author" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>" v-cloak>
	<input type="hidden" ref="input" name="vx_author" value="<?= $author ? $author->get_id() : '' ?>">
	<div v-if="author" class="author-details">
		<div class="author-avatar" v-html="author.avatar"></div>
		<div class="author-name">
			<a :href="author.edit_link" class="author-link">{{ author.display_name }}</a>
			<span class="author-role">{{ author.roles.join(', ') }}</span>
		</div>
	</div>
	<a href="#" @click.prevent="search.show = !search.show" class="change-author preview button">Change author</a>
	<div v-if="search.show" class="search-author">
		<div>

			<input type="text" v-model="search.term" placeholder="Search users..." @keydown.enter.prevent="searchUsers">
			<small><i>Press enter to submit search</i></small>
		</div>
		<div class="search-results">
			<template v-if="search.results !== null">

				<template v-if="search.results.length">
					<template v-for="user in search.results">
						<a href="#" class="single-result" @click.prevent="setAuthor(user)">
							<div class="author-details">
								<div class="author-avatar" v-html="user.avatar"></div>
								<div class="author-name">
									<div class="author-link">{{ user.display_name }}</div>
									<span class="author-role">{{ user.roles.join(', ') }}</span>
								</div>
							</div>
						</a>
					</template>
					<small><i>Click to select user</i></small>
				</template>
				<template v-else>
					No results.
				</template>
			</template>
		</div>
	</div>
</div>
