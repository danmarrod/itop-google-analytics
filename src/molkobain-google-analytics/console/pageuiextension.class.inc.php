<?php

/**
 * Copyright (c) 2015 - 2020 Molkobain.
 *
 * This file is part of licensed extension.
 *
 * Use of this extension is bound by the license you purchased. A license grants you a non-exclusive and non-transferable right to use and incorporate the item in your personal or commercial projects. There are several licenses available (see https://www.molkobain.com/usage-licenses/ for more informations)
 */

namespace Molkobain\iTop\Extension\GoogleAnalytics\Console\Extension;

use iPageUIExtension;
use iTopWebPage;
use Molkobain\iTop\Extension\GoogleAnalytics\Common\Helper\ConfigHelper;

/**
 * Class PageUIExtension
 *
 * @package Molkobain\iTop\Extension\GoogleAnalytics\Console\Extension
 */
class PageUIExtension implements iPageUIExtension
{
	/**
	 * @inheritdoc
	 * @throws \CoreException
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage)
	{
		// Check if enabled
		if (ConfigHelper::IsEnabled() === false) {
			return;
		}

		// Check if tracking code defined
		$sTrackingCode = ConfigHelper::GetPortalTrackingCode('backoffice');
		if (empty($sTrackingCode)) {
			return;
		}

		// Check if user should be tracked
		if (ConfigHelper::IsTrackedUser() === false) {
			return;
		}

		$username = ConfigHelper::GetUsername();
		$organization = ConfigHelper::GetOrganization();
		$oPage->add_ready_script(
			<<<JS
	<!-- Initialize datalayer -->
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({'event': 'Login','userId':'{$username}'});
	dataLayer.push({'event': 'Team','organization':'{$organization}'});
	<!-- End Initialize datalayer  -->

	<!-- Google Tag Manager -->
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','{$sTrackingCode}');
	<!-- End Google Tag Manager -->
JS
		);
	}

	/**
	 * @inheritdoc
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage)
	{
		// Nothing to do
	}

	/**
	 * @inheritdoc
	 */
	public function GetBannerHtml(iTopWebPage $oPage)
	{
		// Nothing to do
	}
}
