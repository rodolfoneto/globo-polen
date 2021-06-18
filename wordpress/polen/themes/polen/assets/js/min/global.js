const CONSTANTS={MESSAGE_COOKIE:"message_cookie",SUCCESS:"success",ERROR:"error",SHOW:"show",HIDDEN:"hidden",MESSAGE_TIME:10,THEME:"theme_mode"};var interval=setInterval;function copyToClipboard(e){var t=document.createElement("input");t.id="share-input",t.style="position: fixed; top: 500vh",document.body.appendChild(t),t.value=e,t.select(),t.setSelectionRange(0,99999),document.execCommand("copy"),document.body.removeChild(t),polMessage("Sucesso","Link copiado para Área de transferência")}function docReady(e){"complete"===document.readyState||"interactive"===document.readyState?setImediate(e):document.addEventListener("DOMContentLoaded",e)}function shareVideo(e,t){var n={title:e,url:t};if(navigator.share)try{navigator.share(n).then(()=>{console.log("Sucesso!","Link compartilhado com sucesso")}).catch(console.error)}catch(e){polError("Error: "+e)}else copyToClipboard(n.url)}function changeHash(e){window.location.hash=e||""}function setImediate(e){setTimeout(e,1)}function polMessageKill(e){clearInterval(interval);var t=document.getElementById(e);t&&(t.classList.remove(CONSTANTS.SHOW),setImediate((function(){t.parentNode.removeChild(t)})))}function polMessageAutoKill(e){interval=setInterval((function(){polMessageKill(e)}),1e3*CONSTANTS.MESSAGE_TIME)}function polSpinner(e,t){if(e===CONSTANTS.HIDDEN)polMessageKill("pol-fog");else{polMessageKill("pol-fog");var n=null,s=document.createElement("div");s.id="pol-fog",s.classList.add("fog"),s.innerHTML='\n\t\t\t<div class="spinner">\n\t\t\t\t<div class="spinner-border text-primary" role="status">\n\t\t\t\t\t<span class="sr-only">Aguarde...</span>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t',(n=document.querySelector(t))?s.classList.add("inner"):n=document.body,n.appendChild(s),setImediate((function(){s.classList.add(CONSTANTS.SHOW)}))}}const polMessages={message:function(e,t){polMessage(e,t)},error:function(e){polError(e)}};function polMessage(e,t){var n="message-box";polMessageKill(n);var s=document.createElement("div");s.id=n,s.classList.add(n),s.classList.add(CONSTANTS.SUCCESS),s.innerHTML=`\n\t<div class="row">\n\t\t<div class="col-md-12">\n\t\t\t<i class="bi bi-check-circle" style="color: var(--success)"></i>\n\t\t</div>\n\t\t<div class="col-md-12">\n\t\t\t<h4 class="message-title">${e}</h4>\n\t\t\t<p class="message-text mt-1">${t}</p>\n\t\t</div>\n\t</div>\n\t<button class="message-close" onclick="polMessageKill('${n}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(s),setImediate((function(){s.classList.add(CONSTANTS.SHOW),polMessageAutoKill(n)}))}function polError(e){var t="message-box";polMessageKill(t);var n=document.createElement("div");n.id=t,n.classList.add(t),n.classList.add(CONSTANTS.ERROR),n.innerHTML=`\n\t<i class="icon icon-error-o" style="color: var(--danger);"></i>\n\t<p class="message-text px-1">${e}</p>\n\t<button class="message-close" onclick="polMessageKill('${t}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(n),setImediate((function(){n.classList.add(CONSTANTS.SHOW),polMessageAutoKill(t)}))}function truncatedItems(){const e=document.querySelectorAll(".truncate");if(e.length<1)return;const t=new ResizeObserver(e=>{for(let t of e)t.target.classList[t.target.scrollHeight>t.contentRect.height+1?"add":"remove"]("truncated")});e.forEach(e=>{t.observe(e)})}function setSessionMessage(e=CONSTANTS.SUCCESS,t="Obrigado!",n){sessionStorage.setItem(CONSTANTS.MESSAGE_COOKIE,JSON.stringify({type:e,title:t,message:n}))}function getSessionMessage(){var e=sessionStorage.getItem(CONSTANTS.MESSAGE_COOKIE);if(e){var t=JSON.parse(e);t.type===CONSTANTS.SUCCESS?polMessage(t.title,t.message):t.type===CONSTANTS.ERROR&&polError(t.message),sessionStorage.removeItem(CONSTANTS.MESSAGE_COOKIE)}}function blockUnblockInputs(e,t){document.querySelectorAll(`${e} input, ${e} select, ${e} textarea`).forEach((function(e,n,s){t?e.setAttribute("readonly",t):e.removeAttribute("readonly")})),console.log("blocked inputs",t)}function downloadClick_handler(e){e.preventDefault();let t={hash:jQuery(e.currentTarget).attr("data-download"),security:jQuery(e.currentTarget).attr("data-nonce"),action:"video-download-link"};jQuery.post(woocommerce_params.ajax_url,t,e=>{e.success&&(window.location.href=e.data)})}const GA_EVENTS={PURCHASE:"purchase"};function polenGA(e,t){gtag("event",e,t)}jQuery(document).ready((function(){truncatedItems(),getSessionMessage()})),function(e){e(document).on("click",".signin-newsletter-button",(function(t){t.preventDefault();var n=e('input[name="signin_newsletter"]'),s=e('input[name="signin_newsletter_page_source"]'),o=e('input[name="signin_newsletter_event"]'),a=e('input[name="signin_newsletter_is_mobile"]'),i=e(this).attr("code");e(".signin-response").html(""),""!==n.val()?(polSpinner(CONSTANTS.SHOW,"#signin-newsletter"),e.ajax({type:"POST",url:woocommerce_params.ajax_url,data:{action:"polen_newsletter_signin",security:i,email:n.val(),page_source:s.val(),event:o.val(),is_mobile:a.val()},success:function(e){polMessage("Seu email foi adicionado à lista",e.data.response),n.val("")},complete:function(){polSpinner(CONSTANTS.HIDDEN)},error:function(e,t,n){polError("Erro: "+e.responseJSON.data.response)}})):polError("Por favor, digite um e-mail válido")}))}(jQuery);