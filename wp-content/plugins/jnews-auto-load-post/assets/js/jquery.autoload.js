!function(t){"use strict";var e=function(e,n){this.content_container=".post-wrapper",this.content_class=".post-autoload",this.current_url=window.location.href,this.post_cache=[],this.is_sidefeed=!!t("#jeg_sidecontent").length,this.sidefeed=t(".jeg_sidefeed"),this.change_url_locked=!1,this.is_sidefeed=!this.sidefeed.hasClass("sidefeed_sidebar")&&this.is_sidefeed,t(document).on("jnews-sidefeed-ajax",t.proxy(this.clear_post_cache,this)),t(document).on("jnews-sidefeed-ajax-begin",t.proxy(this.lock_change_url,this)),this.init()};e.DEFAULTS={},e.prototype.lock_change_url=function(){this.change_url_locked=!0},e.prototype.init=function(){this.initialise_waypoint()},e.prototype.clear_post_cache=function(){this.change_url_locked=!1,this.post_cache=[],this.initialise_waypoint()},e.prototype.find_prev_post=function(e){return(e=t(e).next()).length?e.hasClass("jeg_post")?e:this.find_prev_post(e):null},e.prototype.get_previous_url=function(e,n){if(this.is_sidefeed&&"sidefeed"===jnewsoption.autoload_content||this.is_sidefeed&&window.jnews&&window.jnews.sidefeed&&window.jnews.sidefeed.variable.include_category){var o=this.sidefeed.find('[data-id="'+n+'"]'),i=this.find_prev_post(o);return i?t(i).find("a.ajax").attr("href"):null}return t(e).data("prev")},e.prototype.initialise_waypoint=function(){var e=this;t(e.content_class).each((function(){t(this).hasClass("loaded")||(t(this).addClass("loaded").waypoint((function(n){if(!e.change_url_locked){t.proxy(e.change_url,e,this.element,n).call()}}),{offset:"0%",context:window}),t(this).find(".jnews-autoload-splitter").waypoint((function(n){if(!e.change_url_locked){var o=t(this.element).parents(".post-wrap");t.proxy(e.change_url,e,o,n).call()}}),{offset:"0%",context:window}))}))},e.prototype.strip_html=function(t){var e=document.createElement("DIV");return e.innerHTML=t,e.textContent||e.innerText||""},e.prototype.change_url=function(e,n){var o=t(e),i=o.data("url"),a=o.data("title"),s=o.data("id"),d=this.get_previous_url(o,s);if(this.current_url!=i&&(this.current_url=i,history&&history.pushState&&(history.pushState(null,a,i),document.title=this.strip_html(a)),window.jnews&&window.jnews.ajax_analytic&&jnews.ajax_analytic.update(i,s),t(document).trigger("jnews-autoload-change-id",[s])),jnewsoption.autoload_limit&&t(this.content_class).length>parseInt(jnewsoption.autoload_limit))return!1;d&&"down"===n&&this.auto_load_prev_post(d,s)},e.prototype.check_loaded=function(t){return this.post_cache.indexOf(t)>-1},e.prototype.push_post_id=function(t){this.post_cache.push(t)},e.prototype.auto_load_prev_post=function(e,n){var o=this,i=null;if(e&&!o.check_loaded(n)){if(o.push_post_id(n),e.indexOf("?p=")>-1)i=e+"&autoload=1";else{var a="autoload/";"/"!=e.charAt(e.length-1)&&(a="/"+a),i=e+a}t.get(i,(function(e){t(o.content_container).append(e),o.initialise_waypoint(),t(document).trigger("jnews-ajax-load",[t(o.content_container).find(o.content_class).last()])}))}};var n=t.fn.jautoload;t.fn.jautoload=function(n){return t(this).each((function(){var o=t(this),i=t.extend({},e.DEFAULTS,o.data(),"object"==typeof n&&n),a=o.data("jeg.autoload");a||o.data("jeg.autoload",a=new e(i))}))},t.fn.jautoload.Constructor=e,t.fn.jautoload.noConflict=function(){return t.fn.jautoload=n,this},t(document).ready((function(){t("body").jautoload()}))}(jQuery);