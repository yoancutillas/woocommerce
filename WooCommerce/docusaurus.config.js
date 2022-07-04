// @ts-check
// Note: type annotations allow type checking and IDEs autocompletion

const lightCodeTheme = require( 'prism-react-renderer/themes/github' );
const darkCodeTheme = require( 'prism-react-renderer/themes/dracula' );

/** @type {import('@docusaurus/types').Config} */
const config = {
	title: 'My Site',
	tagline: 'Dinosaurs are cool',
	url: 'https://your-docusaurus-test-site.com',
	baseUrl: '/',
	onBrokenLinks: 'throw',
	onBrokenMarkdownLinks: 'warn',
	favicon: 'img/favicon.ico',

	// GitHub pages deployment config.
	// If you aren't using GitHub pages, you don't need these.
	organizationName: 'facebook', // Usually your GitHub org/user name.
	projectName: 'docusaurus', // Usually your repo name.

	// Even if you don't use internalization, you can use this field to set useful
	// metadata like html lang. For example, if your site is Chinese, you may want
	// to replace "en" with "zh-Hans".
	i18n: {
		defaultLocale: 'en',
		locales: [ 'en' ],
	},

	presets: [
		[
			'classic',
			/** @type {import('@docusaurus/preset-classic').Options} */
			( {
				docs: {
					path: '../docs',
					routeBasePath: '/',
					sidebarPath: require.resolve( './sidebars.js' ),
					showLastUpdateTime: true,
					editUrl:
						'https://github.com/reduxjs/redux/edit/master/website',
				},
				theme: {
					customCss: require.resolve( './src/css/custom.css' ),
				},
			} ),
		],
	],
	plugins: [
		[
			'@docusaurus/plugin-content-docs',
			{
				id: 'woocommerce-admin',
				routeBasePath: 'woocommerce-admin',
				path: '../plugins/woocommerce-admin/docs',
				sidebarPath: require.resolve( './sidebarsWCA.js' ),
			},
		],
		require.resolve( '@cmfcmf/docusaurus-search-local' ),
	],

	themeConfig:
		/** @type {import('@docusaurus/preset-classic').ThemeConfig} */
		( {
			navbar: {
				title: 'My Site',
				logo: {
					alt: 'My Site Logo',
					src: 'img/logo.svg',
				},
				items: [
					{
						label: 'Docs',
						to: 'intro',
					},
				],
			},
			footer: {
				style: 'dark',
				links: [
					{
						title: 'Docs',
						items: [
							{
								label: 'Tutorial',
								to: '/docs/intro',
							},
						],
					},
				],
				copyright: `Copyright © ${ new Date().getFullYear() } My Project, Inc. Built with Docusaurus.`,
			},
			prism: {
				theme: lightCodeTheme,
				darkTheme: darkCodeTheme,
			},
		} ),
};

module.exports = config;
