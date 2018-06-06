if(window.__pushToken != undefined) {

    firebase.initializeApp({
        messagingSenderId: window.__pushToken
    })
    const messaging = firebase.messaging();

    var swPath = '/assets/firebase-messaging-sw.js';

    $(function () {
        if ('Notification' in window == false) {
            return;
        }

        /* Получает токен сервис воркера или показывает диалог подписки*/
        getSWToken(function (token) {
            if (token == null) {
                subscribeSWToken();
            } else if (!localStorage.getItem('firebaseToken') || token != localStorage.getItem('firebaseToken')) {
                sendSWTokenToServer(token);
            }
        });

        /*Отпиливатеся старый сервис воркер если есть новый*/
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function (registrations) {
                if (registrations.length > 1) {
                    for (var i in registrations) {
                        if (registrations[i].active && registrations[i].active.scriptURL.indexOf('service-worker.js') != -1) {
                            registrations[i].unregister().then(function (boolean) {
                            });
                        }
                    }
                }
            });
        }
        // При обновлении токена, новый токен отправляется на сервер
        messaging.onTokenRefresh(function () {
            messaging.getToken()
                .then(function (refreshedToken) {
                    sendSWTokenToServer(refreshedToken);
                })
                .catch(function (err) {
                    console.log('Не удается обновить токен ', err);
                });
        });

        messaging.onMessage(function (payload) {
            var options = payload.data;
            options.requireInteraction = true;
            options.data = payload.data;

            $.post('/push/received/' + options.postId, {id: options.postId});

            navigator.serviceWorker.getRegistrations().then(function (registrations) {
                registrations[0].showNotification(options.title, options);
            })

        });

        /** Получить текущий токен fcm */
        function getSWToken(callback) {

            return messaging.getToken()
                .then(function (currentToken) {
                    if (currentToken) {
                        if (typeof callback == 'function') {
                            callback(currentToken);
                        }
                    } else {
                        callback(null);
                    }
                })
                .catch(function (err) {
                    console.log('An error occurred while retrieving token. ', err);
                });
        }

        /** Зарегить воркер и получить токен fcm */
        function subscribeSWToken() {
            navigator.serviceWorker.register(swPath).then(function (registration) {
                window.swregistration = registration;
                navigator.serviceWorker.ready.then(function (registration) {
                    return registration.pushManager.subscribe({userVisibleOnly: true}).then(function (subscription) {
                        sendSWTokenToServer(subscription.endpoint);
                    });
                })
            }).catch(function (err) {
                return console.log('ServiceWorker registration failed: ', err);
            });


            messaging.requestPermission()
                .then(function () {
                    getSWToken(function (token) {
                        sendSWTokenToServer(token);
                    })
                })
                .catch(function (err) {

                });

        }

        /* Отправить токен на сервер*/
        function sendSWTokenToServer(currentToken) {
            if (currentToken == localStorage.getItem('firebaseToken')) return;
            $.post('/push/endpoint', {endpoint: currentToken});
            localStorage.setItem('firebaseToken', currentToken);
        }

        /* Показать окно подписки на пуши*/
        function showSWRegisterDialog() {
            if (!$.cookie('showPopUpSubscribe')) {
                $('.popup_btn.yes').on('click', function () {

                    $('.popup_block').fadeOut(400);
                    $('.popup_block').removeClass('show');
                    $.cookie('showPopUpSubscribe', 'true', {expires: 3});
                    subscribeSWToken();
                });

                $('.popup_btn.no').on('click', function () {
                    $('.popup_block').fadeOut(400);
                    $('.popup_block').removeClass('show');
                    $.cookie('showPopUpSubscribe', 'true', {expires: 3});
                });
                setTimeout(function () {
                    $('.popup_block').fadeIn(400);
                    $('.popup_block').addClass('show');
                }, 1000)
            }
        }

    });
}


