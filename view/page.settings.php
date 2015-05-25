<?php
	$_UserLogged->init_settings();
?>
<section id="page-settings">
	<section id="container-settings">
		<div class="head-settings">
			<span class="very-big-title">Paramètres</span>
		</div>
		<section class="all-settings">
			<div class="one-setting">
				<div class="the-setting">
					<span class="name-setting">Votre adresse e-mail</span>
					<input type="email" name="settings[email]" class="edit-setting edit-Email" value="<?php echo $_UserLogged->email;?>">
				</div>
			</div>
			<div class="one-setting">
				<div class="the-setting">
					<span class="name-setting">Votre adresse de profil</span>
					<span class="setting-prefix-input">http://the1958.fr/@</span><input type="text" name="settings[nickname]" class="edit-setting edit-Nickname" value="<?php echo $_UserLogged->nickname;?>">
				</div>
			</div>
			<!-- <div class="one-setting">
				<div class="the-setting">
					<span class="name-setting">Activités sur vos contenues</span>
					<span class="setting-description-input">Nous vous enverons un email quand il y aura des activités sur vos critiques et micros-critiques.</span>
				</div>
			</div>
			<div class="one-setting">
				<div class="the-setting">
					<span class="name-setting">Activités des mes abonnements</span>
					<span class="setting-description-input">Nous vous enverons un email vos abonnements publieronts de nouveaux contenues, ainsi que leurs recommendations.</span>
				</div>
			</div> -->
		</section>
	</section>
</section>