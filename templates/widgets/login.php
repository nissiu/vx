<?php
/**
 * Auth widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="ts-auth hidden" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<div v-if="screen === 'login'" class="ts-form ts-login">
		<form @submit.prevent="submitLogin">
			<div class="ts-login-head">
				<p><?php echo $this->get_settings_for_display( 'auth_title' ); ?></p>
			</div>

			<?php if ( \Voxel\get( 'settings.auth.google.enabled' ) ): ?>
				<div class="login-section">
					<div class="ts-form-group">
						<label><?= _x( 'Connect with social media', 'auth', 'voxel' ) ?></label>
					</div>
					<div class="ts-form-group ts-social-connect">
						<a href="<?= esc_url( \Voxel\get_google_auth_link() ) ?>" class="ts-btn ts-google-btn ts-btn-large ts-google-btn">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_google_ico') ) ?: \Voxel\svg( 'google.svg' ) ?>
							<?= _x( 'Sign in with Google', 'auth', 'voxel' ) ?>
						</a>
					</div>
				</div>
			<?php endif ?>

			<div class="login-section">
				<div class="ts-form-group">
					<label><?= _x( 'Enter your details', 'auth', 'voxel' ) ?></label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
						<input type="text" v-model="login.username" placeholder="<?= esc_attr( _x( 'Username', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group ts-password-field">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<input type="password" v-model="login.password" ref="loginPassword" placeholder="<?= esc_attr( _x( 'Password', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
						<?= _x( 'Login', 'auth', 'voxel' ) ?>
					</button>
				</div>
				
			</div>
			<div class="login-section">
				<div v-if="config.register_enabled" class="ts-form-group">
					<label>
						<?= _x( 'Don\'t have an account?', 'auth', 'voxel' ) ?>
						<a href="#" @click.prevent="screen = 'register'"><?= _x( 'Sign up', 'auth', 'voxel' ) ?></a>
					</label>
				</div>
				<div class="ts-form-group">
					<label>
						<?= _x( 'Forgot password?', 'auth', 'voxel' ) ?>
						<a href="#" @click.prevent="screen = 'recover'"><?= _x( 'Recover account', 'auth', 'voxel' ) ?></a>
					</label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover'" class="ts-form ts-login">
		<form @submit.prevent="submitRecover">
			<div class="ts-form-group">
				<label><?= _x( 'Reset your password', 'auth', 'voxel' ) ?></label>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
					<input type="email" v-model="recovery.email" placeholder="<?= esc_attr( _x( 'Your account email', 'auth', 'voxel' ) ) ?>" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
					<?= _x( 'Reset password', 'auth', 'voxel' ) ?>
				</button>
			</div>
			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'login'" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_chevron_left') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
					<?= __( 'Go back', 'voxel' ) ?>
				</a>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover_confirm'" class="ts-form ts-login">
		<form @submit.prevent="submitRecoverConfirm">
			<div class="login-section">
				<div class="ts-form-group">
					<label><?= _x( 'Password recovery', 'auth', 'voxel' ) ?></label>
					<small><?= _x( 'Please type the recovery code which was sent to your email', 'auth', 'voxel' ) ?></small>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
						<input type="text" v-model="recovery.code" placeholder="<?= esc_attr( _x( 'Confirmation code', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?= _x( 'Submit', 'auth', 'voxel' ) ?>
					</button>
				</div>
			
				<div class="ts-form-group">
					<label>
						<?= _x( 'Didn\'t receive anything?', 'auth', 'voxel' ) ?>
						<a href="#" @click.prevent="recovery.code = null; screen = 'recover';"><?= _x( 'Send again', 'auth', 'voxel' ) ?></a>
					</label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover_set_password'" class="ts-form ts-login">
		<form @submit.prevent="submitNewPassword">
			<div class="ts-form-group">
				<label><?= _x( 'Choose your new password', 'auth', 'voxel' ) ?></label>
				<small><?= _x( 'Password must contain at least 8 characters.', 'auth', 'voxel' ) ?></small>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
					<input type="password" v-model="recovery.password" placeholder="<?= esc_attr( _x( 'Your new password', 'auth', 'voxel' ) ) ?>" class="autofocus">
				</div>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
					<input type="password" v-model="recovery.confirm_password" placeholder="<?= esc_attr( _x( 'Confirm password', 'auth', 'voxel' ) ) ?>" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					<?= _x( 'Save changes', 'auth', 'voxel' ) ?>
				</button>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'register'" class="ts-form ts-login">
		<form @submit.prevent="submitRegister">
			<div class="ts-login-head">
				<p><?php echo $this->get_settings_for_display( 'auth_reg_title' ); ?></p>
			</div>

			<?php if ( \Voxel\get( 'settings.auth.google.enabled' ) ): ?>
				<div class="login-section">
					<div class="ts-form-group">
						<label><?= _x( 'Connect with social media', 'auth', 'voxel' ) ?></label>
					</div>
					<div class="ts-form-group ts-social-connect">
						<a href="<?= esc_url( \Voxel\get_google_auth_link() ) ?>" class="ts-btn  ts-google-btn ts-btn-large">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_google_ico') ) ?: \Voxel\svg( 'google.svg' ) ?>
							<?= _x( 'Sign in with Google', 'auth', 'voxel' ) ?>
						</a>
					</div>
				</div>
			<?php endif ?>

			<div class="login-section">
				<div class="ts-form-group login-form-heading">
					<label><?= _x( 'Enter your details', 'auth', 'voxel' ) ?></label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
						<input type="text" v-model="register.username" placeholder="<?= esc_attr( _x( 'Username', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
						<input type="email" v-model="register.email" placeholder="<?= esc_attr( _x( 'Email address', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<input type="password" v-model="register.password" placeholder="<?= esc_attr( _x( 'Password', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
			</div>
			<div class="login-section">
				<div class="ts-form-group">
					<label>
						<?= \Voxel\replace_vars( _x( 'I agree to the <a:terms>Terms and Conditions</a> and <a:privacy>Privacy Policy</a>', 'auth', 'voxel' ), [
							'<a:terms>' => '<a target="_blank" href="'.esc_url( get_permalink( \Voxel\get( 'templates.terms' ) ) ?: home_url('/') ).'">',
							'<a:privacy>' => '<a target="_blank" href="'.esc_url( get_permalink( \Voxel\get( 'templates.privacy_policy' ) ) ?: home_url('/') ).'">'
						] ) ?>
					</label>
				</div>
				<div class="ts-form-group">
					<div class="switch-slider">
						<div class="onoffswitch"><input type="checkbox" v-model="register.terms_agreed" class="onoffswitch-checkbox" tabindex="0">
							<label class="onoffswitch-label" @click="register.terms_agreed = !register.terms_agreed"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="login-section">
				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
						<?= _x( 'Sign up', 'auth', 'voxel' ) ?>
					</button>
				</div>
			</div>
			<div class="login-section">
				<div class="ts-form-group">
					<label>
						<?= _x( 'Have an account already?', 'auth', 'voxel' ) ?>
						<a href="#" @click.prevent="screen = 'login'"><?= _x( 'Login instead', 'auth', 'voxel' ) ?></a>
					</label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'confirm_account' || screen === 'login_confirm_account'" class="ts-form ts-login">
		<form @submit.prevent="submitConfirmAccount( screen === 'login_confirm_account' ? 'login' : 'register' )">
			<div class="ts-form-group">
				<label>
					<?= _x( 'Confirm your email', 'auth', 'voxel' ) ?>
					<small><?= _x( 'Please type the confirmation code which was sent to your email', 'auth', 'voxel' ) ?></small>
				</label>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
					<input type="text" v-model="confirmation_code" placeholder="<?= esc_attr( _x( 'Confirmation code', 'auth', 'voxel' ) ) ?>" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					<?= _x( 'Submit', 'auth', 'voxel' ) ?>
				</button>
			</div>

			<div class="login-section">
				<div class="ts-form-group">
					<label>
						<?= _x( 'Didn\'t receive code?', 'auth', 'voxel' ) ?>
						<a
							href="#"
							@click.prevent="resendConfirmationCode( screen === 'login_confirm_account' ? 'login' : 'register' )"
							:class="{'vx-pending': resendCodePending}"
						><?= _x( 'Resend email', 'auth', 'voxel' ) ?></a>
					</label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'security'" class="ts-form ts-login">
		<div class="ts-login-head">
			<p><?= _x( 'Account security', 'auth', 'voxel' ) ?></p>
		</div>

		<div class="login-section">
			<div class="ts-form-group">
				<a href="<?= esc_url( home_url('/') ) ?>" @click.prevent="screen = 'security_update_email'" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
					<?= _x( 'Update email address', 'auth', 'voxel' ) ?>
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( home_url('/') ) ?>" @click.prevent="screen = 'security_update_password'" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
					<?= _x( 'Update password', 'auth', 'voxel' ) ?>
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( \Voxel\get_logout_url() ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
					<?= _x( 'Logout', 'auth', 'voxel' ) ?>
				</a>
			</div>
		</div>
	</div>

	<div v-else-if="screen === 'security_update_password'" class="ts-form ts-login">
		<form @submit.prevent="submitUpdatePassword">
			<template v-if="update.password.successful">
				<div class="ts-form-group">
					<label><?= _x( 'Your password has been updated.', 'auth', 'voxel' ) ?></label>
				</div>
			</template>
			<template v-else>
				<div class="ts-form-group">
					<label><?= _x( 'Enter your current password', 'auth', 'voxel' ) ?></label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<input type="password" v-model="update.password.current" placeholder="<?= esc_attr( _x( 'Current password', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<label><?= _x( 'Choose new password', 'auth', 'voxel' ) ?></label>
					<small><?= _x( 'Password must contain at least 8 characters.', 'auth', 'voxel' ) ?></small>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<input type="password" v-model="update.password.new" placeholder="<?= esc_attr( _x( 'Your new password', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<input type="password" v-model="update.password.confirm_new" placeholder="<?= esc_attr( _x( 'Confirm password', 'auth', 'voxel' ) ) ?>" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_pass_ico') ) ?: \Voxel\svg( 'lock-alt.svg' ) ?>
						<?= _x( 'Update password', 'auth', 'voxel' ) ?>
					</button>
				</div>
			</template>

			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'security'" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_chevron_left') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
					<?= __( 'Go back', 'voxel' ) ?>
				</a>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'security_update_email'" class="ts-form ts-login">
		<form @submit.prevent="submitUpdateEmail">
			<template v-if="update.email.state === 'confirmed'">
				<div class="ts-form-group">
					<label><?= _x( 'Your email address has been updated.', 'auth', 'voxel' ) ?></label>
				</div>
			</template>
			<template v-else>
				<div class="ts-form-group">
					<label><?= _x( 'Your current email address', 'auth', 'voxel' ) ?></label>
				</div>
				<?php if ( is_user_logged_in() ): ?>
					<div class="ts-form-group">
						<div class="ts-input-icon flexify">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
							<input type="email" disabled value="<?= esc_attr( \Voxel\current_user()->get_email() ) ?>" class="autofocus">
						</div>
					</div>
				<?php endif ?>
				<div class="ts-form-group">
					<label><?= _x( 'Enter new email address', 'auth', 'voxel' ) ?></label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
						<input type="email" v-model="update.email.new" placeholder="<?= esc_attr( _x( 'Enter email address', 'auth', 'voxel' ) ) ?>" class="autofocus" :disabled="update.email.state !== 'send_code'">
					</div>
				</div>

				<template v-if="update.email.state === 'verify_code'">
					<div class="ts-form-group">
						<label><?= _x( 'Confirmation code', 'auth', 'voxel' ) ?></label>
						<small><?= _x( 'Please type the confirmation code which sent to your new email', 'auth', 'voxel' ) ?></small>
					</div>
					<div class="ts-form-group">
						<div class="ts-input-icon flexify">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
							<input type="text" v-model="update.email.code" placeholder="<?= esc_attr( _x( 'Confirmation code', 'auth', 'voxel' ) ) ?>" class="autofocus">
						</div>
					</div>
				</template>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_email_ico') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
						<template v-if="update.email.state === 'send_code'">
							<?= _x( 'Send confirmation code', 'auth', 'voxel' ) ?>
						</template>
						<template v-else>
							<?= _x( 'Update email address', 'auth', 'voxel' ) ?>
						</template>
					</button>
				</div>
			</template>

			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'security'" class="ts-btn ts-btn-1 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_chevron_left') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
					<?= __( 'Go back', 'voxel' ) ?>
				</a>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'welcome'" class="ts-form ts-login ts-welcome">
		<div class="login-section">
			<div class="ts-welcome-message ts-form-group">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_welcome_ico') ) ?: \Voxel\svg( 'happy-2.svg' ) ?>
				<h2><?php echo $this->get_settings_for_display( 'auth_welc_title' ); ?></h2>
				<label><?php echo $this->get_settings_for_display( 'auth_welc_subtitle' ); ?></label>
			</div>
		</div>
		<div class="login-section">
			<div class="ts-form-group">
				<a :href="config.editProfileUrl" class="ts-btn ts-btn-2 ts-btn-large">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('auth_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
					<?= _x( 'Complete profile', 'auth', 'voxel' ) ?>
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( $config['redirectUrl'] ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
					<?= _x( 'Do it later', 'auth', 'voxel' ) ?>
				</a>
			</div>
		</div>
	</div>
</div>
