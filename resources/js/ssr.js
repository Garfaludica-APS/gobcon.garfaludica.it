import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { i18nVue } from 'laravel-vue-i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const orgName = import.meta.env.VITE_ORG_NAME || 'Garfaludica APS';

createServer((page) =>
	createInertiaApp({
		page,
		render: renderToString,
		title: (title) => `${title} - ${orgName}`,
		resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
		setup({ App, props, plugin }) {
			return createSSRApp({ render: () => h(App, props) })
				.use(plugin)
				.use(ZiggyVue, {
					...page.props.ziggy,
					location: new URL(page.props.ziggy.url),
				})
				.mixin({
					methods: {
						lroute: function (name, params, absolute, config)
						{
							if (name && Ziggy.routeNamePrefix !== undefined)
								name = Ziggy.routeNamePrefix + name;
							return route(name, params, absolute, config);
						},
						currentRoute: function (name)
						{
							var r = route().current();
							if (r.startsWith('en.'))
								r = r.substr(3);
							if (!name)
								return r;
							return r === name;

						},
					},
				})
				.use(i18nVue, {
					lang: 'en',
					resolve: lang => {
						const langs = import.meta.glob('../../lang/*.json', { eager: true });
						return langs[`../../lang/${lang}.json`].default;
					},
				});
		},
	})
);
