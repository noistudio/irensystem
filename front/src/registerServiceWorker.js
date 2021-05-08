/* eslint-disable no-console */

import {register} from 'register-service-worker'
import VueRegister_serviceConfig from "../vue.register_service.config";


var base_url = VueRegister_serviceConfig.getBaseURL();


if (process.env.NODE_ENV === 'production') {
	register(`${base_url}service-worker.js`, {
		ready() {
			console.log(
				'App is being served from cache by a service worker.\n' +
				'For more details, visit https://goo.gl/AFskqB'
			)
		},
		registered() {
			console.log('Service worker has been registered.')
		},
		cached() {
			console.log('Content has been cached for offline use.')
		},
		updatefound() {
			console.log('New content is downloading.')
		},
		updated() {
			console.log('New content is available; please refresh.')
		},
		offline() {
			console.log('No internet connection found. App is running in offline mode.')
		},
		error(error) {
			console.error('Error during service worker registration:', error)
		}
	})
}
