<?php

/**
 * Copyright (c) 2015 - 2020 Molkobain.
 *
 * This file is part of licensed extension.
 *
 * Use of this extension is bound by the license you purchased. A license grants you a non-exclusive and non-transferable right to use and incorporate the item in your personal or commercial projects. There are several licenses available (see https://www.molkobain.com/usage-licenses/ for more informations)
 */

namespace Molkobain\iTop\Extension\GoogleAnalytics\Portal\Extension;

use AbstractPortalUIExtension;
use Molkobain\iTop\Extension\GoogleAnalytics\Common\Helper\ConfigHelper;
use Symfony\Component\DependencyInjection\Container;

// Protection for iTop 2.6 and older
if (!class_exists('Molkobain\\iTop\\Extension\\GoogleAnalytics\\Portal\\Extension\\PortalUIExtensionLegacy')) {
	/**
	 * Class PortalUIExtension
	 *
	 * @package Molkobain\iTop\Extension\GoogleAnalytics\Portal\Extension
	 */
	class PortalUIExtension extends AbstractPortalUIExtension
	{
		/**
		 * @inheritdoc
		 * @throws \CoreException
		 */
		public function GetJSFiles(Container $oContainer)
		{
			$aJSFiles = array();

			// Check if enabled
			if (ConfigHelper::IsEnabled() === false) {
				return $aJSFiles;
			}

			// Check if tracking code defined
			$sTrackingCode = ConfigHelper::GetPortalTrackingCode(PORTAL_ID);
			if (empty($sTrackingCode)) {
				return $aJSFiles;
			}

			// Check if user should be tracked
			if (ConfigHelper::IsTrackedUser() === false) {
				return $aJSFiles;
			}

			$aJSFiles[] = 'https://www.googletagmanager.com/gtag/js?id=' . $sTrackingCode;

			return $aJSFiles;
		}

		/**
		 * @inheritdoc
		 * @throws \CoreException
		 */
		public function GetJSInline(Container $oContainer)
		{
			$sJSInline = '';

			// Check if enabled
			if (ConfigHelper::IsEnabled() === false) {
				return $sJSInline;
			}

			// Check if tracking code defined
			$sTrackingCode = ConfigHelper::GetPortalTrackingCode(PORTAL_ID);
			if (empty($sTrackingCode)) {
				return $sJSInline;
			}

			// Check if user should be tracked
			if (ConfigHelper::IsTrackedUser() === false) {
				return $sJSInline;
			}

			$username = ConfigHelper::GetUsername();
			$organization = ConfigHelper::GetOrganization();
			$sJSInline .= <<<EOF
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
EOF;

			return $sJSInline;
		}
	}
}
