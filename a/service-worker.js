"use strict";var precacheConfig=[["/index.html","65dee1367fdc57034c2a88600ee72d13"],["/static/css/main.css","d41d8cd98f00b204e9800998ecf8427e"],["/static/css/vendor.css","1ba88883dd802f3f5d4218de51c8036e"],["/static/js/0.js","9b23a0546d226cfecc53d739ed248c6e"],["/static/js/1.js","680a5c5dbfed5b340f83d178a33c22d2"],["/static/js/10.js","a7a8206ebaedb0ff8ccd88c2b74ecfce"],["/static/js/11.js","31dd94fc3f805d453923961a04eb3b91"],["/static/js/12.js","b11b9fb3bf7a7710b04c76e87857622e"],["/static/js/13.js","cd3c47318ac2087028a597b43d019f98"],["/static/js/14.js","175e441267e66157a6d13f93778d0a99"],["/static/js/15.js","94443e6476349f07ada6379af9c7ec68"],["/static/js/16.js","8a8923f0b7274704310188de11e48bb5"],["/static/js/17.js","da7971aba49c86829c315fc10a9c08f2"],["/static/js/18.js","5f33c03f41fe3e53059f3b59a798234d"],["/static/js/19.js","a5568adb85e5d896145931500d05d518"],["/static/js/2.js","d74767e8dcb70d0911c1112a2ba3a161"],["/static/js/20.js","4fa3e55a0b87d7a24fe53bc43853b6ab"],["/static/js/21.js","793cf406c53c26d08464ae9a863c63fe"],["/static/js/22.js","aa00d670a16b07c792c645f7c7d0cdde"],["/static/js/23.js","2ffbee4b9fa4da8cdb340e45f9eecfb2"],["/static/js/24.js","3f753bdcb8509e4a04bc407555233b68"],["/static/js/25.js","eaa1eaa718ea0d29ef1f731a741a517d"],["/static/js/26.js","ca7f8e8b03be95827376d5d9a8ba9cc0"],["/static/js/27.js","fbb427edcf741de97650ce34a7b8feb8"],["/static/js/28.js","2072bdb8bf37bd46a26044e5024d7a9f"],["/static/js/29.js","700ecc04ccdd6ff07c3d258dfb9324a8"],["/static/js/3.js","b77c903d8f6100b9cdc65bac7d901d8f"],["/static/js/30.js","b227f9cae3881a89d915743bc0b1434c"],["/static/js/31.js","ad73833848c6b13b0f1ac45fae1c5716"],["/static/js/32.js","3c4104a909a2865749d655c0ce80148b"],["/static/js/33.js","87c67d051b24e4a184aec95a997b2b77"],["/static/js/34.js","3dc3035b4a56d38ab4ce73fb2fa24bd6"],["/static/js/35.js","bf11609accc53b419692d30619ce9f65"],["/static/js/36.js","6708fae9739d860ec2d36781d0db62bd"],["/static/js/37.js","0f3d042e46ff85c219b332195a3630fe"],["/static/js/38.js","3624c30cbd9e3dac989425e97d442016"],["/static/js/39.js","212e37b4c11c94519d29dbd3d16a0337"],["/static/js/4.js","7cddc989e08860494df8a433ce3fb97b"],["/static/js/40.js","f274a8c26903d55c968907026e17ed5c"],["/static/js/41.js","fdfa201ad96b63d62307c93af8fa6e99"],["/static/js/42.js","2d74133246dcac9c6a88da13bd8f7dde"],["/static/js/43.js","d51d3a5154b423981c1a618837ed97a6"],["/static/js/44.js","89b64d469f189ac0c2f4ed0b661cb083"],["/static/js/45.js","192eabad54a7d11de86a66c300a33f6a"],["/static/js/46.js","0f20ead3ee0e1fd65634b6fdd4adbec9"],["/static/js/47.js","8c65e36b6186aea962c9814e89198f48"],["/static/js/48.js","47627c5d7cbc620a4533d27112e44270"],["/static/js/49.js","bc015e2dd7ef88d04f81ed64e52b87b6"],["/static/js/5.js","f77fb450d218a0b7f6761a2119accaf7"],["/static/js/50.js","3284340f97efd1a4c2cbeb513a9551b9"],["/static/js/51.js","9be2dcb0526233615728ae2d2663c8e8"],["/static/js/52.js","a7dfc11642f0d341f4582dc7690ccf54"],["/static/js/53.js","fc927e97676e06f435fc8ee4012cdfda"],["/static/js/54.js","c3261761970043495d99a4304e780309"],["/static/js/55.js","cf51dc4218b07bf4576d572cafe99d85"],["/static/js/56.js","14e76f97109ca0def533eb4e11dcb42d"],["/static/js/57.js","09a181d23959211699fcc9fcf95957c3"],["/static/js/58.js","f02c798c0a8e83239a26ea999d861e93"],["/static/js/59.js","1b663021b456b4b801897ed5199d4068"],["/static/js/6.js","191f86bf18f5b262b3bb45ad8ab24e23"],["/static/js/60.js","c97b0558d01c7d6192d5feadc7e388ee"],["/static/js/61.js","74a768ce83a3577bc4cd7136185b98f5"],["/static/js/62.js","9b75e42eb966968a2db993fe80fbb198"],["/static/js/63.js","6b5d4dc7e20af3651f0f67c0d1e54bc8"],["/static/js/65.js","9569ef69b58b35b3504d3a63bac5e33c"],["/static/js/66.js","a36794748e6c35bdf30021ab9f648420"],["/static/js/67.js","4fdfcecaee0a7da4dc8a9b48cc4a2f32"],["/static/js/68.js","00ad2bc97c789b6547be4409770d30e0"],["/static/js/69.js","f3e91e1e9cf13b02a809feb0d65b6345"],["/static/js/7.js","27c3e864eac3a99da3ca64f514d074d2"],["/static/js/70.js","05348736e65731fcec24e49d9e50f87a"],["/static/js/71.js","b02298c5efbe422b534da7be11ee35d0"],["/static/js/72.js","3b454b08d58dd7b93df12f36563436aa"],["/static/js/8.js","a3c3fe471c4ef844468cb57ed1e6598e"],["/static/js/main.js","98ac18ad8b479995fec6aa316266a822"],["/static/js/vendor.js","42430c14f185c864c3760ba5deabfd91"],["/static/media/exception-403.svg","2dda86ad45caee58c927e111fe3c8071"],["/static/media/exception-404.svg","9ed13bbe02debb8e23db19a4e6603c2e"],["/static/media/exception-500.svg","990ae4d0c56d16d2ffdab6d924b6a179"],["/static/media/fetchStatus-error.png","bcb26fc1dff3fb8fd8faa87ca2d21939"],["/static/media/fetchStatus-nullData.png","5007db43934d9289b2d85ee21971fc68"],["/static/media/images-formula.png","f11cc42b343d3dd137b23e4ef9de76d9"],["/static/media/images-icons.png","9978ec34f943aede6001a2eee52b7e46"],["/static/media/setting-logo.png","106bafd334b8442af66f533410b50575"],["/static/media/shop-dianzi.jpg","03dddae41a61344d9558b9c6c3a81eba"],["/static/media/shop-diyPhone.png","b21e610425d032bbd65b6c56f0a0a2c8"],["/static/media/shop-fen_chaoshi.jpg","f71363c14e0cf1b649bae420bee6d6da"],["/static/media/shop-fen_dianzi.jpg","1f954b7712d1b4c260409c0a28df1fbf"],["/static/media/shop-fen_meizhuang.jpg","15a348fc0f9fab17d018e0bf0d46579f"],["/static/media/shop-fuzhuang.jpg","119100e2480f72be7f9c9297a7d1ddaf"],["/static/media/shop-jiaju.jpg","e90e1efe42ee97645e7fbe706f226244"],["/static/media/shop-meizhuang.jpg","c5e778c91d055c6b6a383b7b377d5634"],["/static/media/shop-muying.jpg","4780f42709362c267d19c227a78aedd0"],["/static/media/shop-qiche.jpg","542f9974fec4b068a217b69278f2ac02"],["/static/media/shop-shengxian.jpg","509c984f5c34b483135cf5dca33e4f75"],["/static/media/wechat-diyPhone.png","b21e610425d032bbd65b6c56f0a0a2c8"]],cacheName="sw-precache-v3-sw-precache-webpack-plugin-"+(self.registration?self.registration.scope:""),ignoreUrlParametersMatching=[/^utm_/],addDirectoryIndex=function(e,a){var c=new URL(e);return"/"===c.pathname.slice(-1)&&(c.pathname+=a),c.toString()},cleanResponse=function(e){return e.redirected?("body"in e?Promise.resolve(e.body):e.blob()).then(function(a){return new Response(a,{headers:e.headers,status:e.status,statusText:e.statusText})}):Promise.resolve(e)},createCacheKey=function(e,a,c,s){var t=new URL(e);return s&&t.pathname.match(s)||(t.search+=(t.search?"&":"")+encodeURIComponent(a)+"="+encodeURIComponent(c)),t.toString()},isPathWhitelisted=function(e,a){if(0===e.length)return!0;var c=new URL(a).pathname;return e.some(function(e){return c.match(e)})},stripIgnoredUrlParameters=function(e,a){var c=new URL(e);return c.hash="",c.search=c.search.slice(1).split("&").map(function(e){return e.split("=")}).filter(function(e){return a.every(function(a){return!a.test(e[0])})}).map(function(e){return e.join("=")}).join("&"),c.toString()},hashParamName="_sw-precache",urlsToCacheKeys=new Map(precacheConfig.map(function(e){var a=e[0],c=e[1],s=new URL(a,self.location),t=createCacheKey(s,hashParamName,c,/\.\w{8}\./);return[s.toString(),t]}));function setOfCachedUrls(e){return e.keys().then(function(e){return e.map(function(e){return e.url})}).then(function(e){return new Set(e)})}self.addEventListener("install",function(e){e.waitUntil(caches.open(cacheName).then(function(e){return setOfCachedUrls(e).then(function(a){return Promise.all(Array.from(urlsToCacheKeys.values()).map(function(c){if(!a.has(c)){var s=new Request(c,{credentials:"same-origin"});return fetch(s).then(function(a){if(!a.ok)throw new Error("Request for "+c+" returned a response with status "+a.status);return cleanResponse(a).then(function(a){return e.put(c,a)})})}}))})}).then(function(){return self.skipWaiting()}))}),self.addEventListener("activate",function(e){var a=new Set(urlsToCacheKeys.values());e.waitUntil(caches.open(cacheName).then(function(e){return e.keys().then(function(c){return Promise.all(c.map(function(c){if(!a.has(c.url))return e.delete(c)}))})}).then(function(){return self.clients.claim()}))}),self.addEventListener("fetch",function(e){if("GET"===e.request.method){var a,c=stripIgnoredUrlParameters(e.request.url,ignoreUrlParametersMatching);(a=urlsToCacheKeys.has(c))||(c=addDirectoryIndex(c,"index.html"),a=urlsToCacheKeys.has(c));!a&&"navigate"===e.request.mode&&isPathWhitelisted(["^(?!\\/__).*"],e.request.url)&&(c=new URL("/index.html",self.location).toString(),a=urlsToCacheKeys.has(c)),a&&e.respondWith(caches.open(cacheName).then(function(e){return e.match(urlsToCacheKeys.get(c)).then(function(e){if(e)return e;throw Error("The cached response that was expected is missing.")})}).catch(function(a){return console.warn('Couldn\'t serve response for "%s" from cache: %O',e.request.url,a),fetch(e.request)}))}});