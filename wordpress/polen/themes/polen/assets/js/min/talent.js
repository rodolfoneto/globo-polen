var modal=document.getElementById("video-modal"),video_box=document.getElementById("video-box"),share_button=document.querySelectorAll(".share-button"),talent_videos=document.getElementById("talent-videos"),public_url=talent_videos?talent_videos.getAttribute("data-public-url"):"",timestamp=function(){var e=0,o=new Date,t=[35,60,180,7200,9e4,345600,864e3][e++]||0;return new Date(o-1e3*t).getTime()/1e3};function generateStoriesArray(e,o){const t=[];return e.map(e=>{t.push([e.hash,"video",0,e.video,"","javascript:clickToBuy()",o?"Doe Agora":"Peça Agora",!1,timestamp()])}),t}function renderStories(e,o,t,n){new Zuck("stories",{backNative:!0,previousTap:!0,backButton:!0,skin:"Snapgram",avatars:!0,paginationArrows:!1,list:!1,cubeEffect:!1,localStorage:!0,language:{unmute:"Toque para ouvir",keyboardTip:"Clique para ver o próximo",visitLink:"Visite o Link",time:{ago:"atrás",hour:"hora",hours:"horas",minute:"minuto",minutes:"minutos",fromnow:"from now",seconds:"segundos",yesterday:"ontem",tomorrow:"amanhã",days:"dias"}},stories:[Zuck.buildTimelineItem(e.length>0?"1":null,t,o,"",timestamp(),generateStoriesArray(e,n))]});if(0===e.length){document.getElementById("stories").setAttribute("id",""),document.getElementsByClassName("story")[0].classList.add("seen"),document.getElementsByClassName("item-link")[0].classList.add("no-link")}}function getVideoId(){return window.location.hash.substring(1)}function changeVideoCardUrl(e){var o=public_url+e,t=document.getElementById("video-url");t&&(t.setAttribute("href",o),t.innerText=o)}function addVideo(){var e=document.createElement("DIV");e.id="polen-video",e.className="polen-video",video_box.appendChild(e)}function killVideo(){var e=document.getElementById("polen-video");e.parentNode.removeChild(e)}function showModal(){document.body.classList.add("no-scroll"),modal.classList.add("show")}function hideModal(e){document.body.classList.remove("no-scroll"),changeHash(),modal.classList.remove("show"),video_box.innerHTML=""}function handleCopyVideoUrl(e){document.getElementById("copy-video").addEventListener("click",(function(){copyToClipboard(public_url+e)}))}function openVideoByURL(e){addVideo(),showModal(),new Vimeo.Player("polen-video",{url:e,autoplay:!0,width:document.getElementById("polen-video").offsetWidth}).getVideoId().then((function(e){changeHash(e),changeVideoCardUrl(e)}))}function openVideoByHash(e){video_box.innerHTML="",polSpinner(null,"#video-box");var o=document.getElementById("product_id").value;const t=`${polenObj.ajax_url}?action=draw-player-modal&hash=${e}&product_id=${o}`;showModal(),changeHash(e),jQuery(video_box).load(t)}function openVideoById(e){addVideo(),showModal();new Vimeo.Player("polen-video",{id:e,autoplay:!0,width:document.getElementById("polen-video").offsetWidth});changeHash(e),changeVideoCardUrl(e)}function clickToBuy(){document.querySelector(".single_add_to_cart_button").click()}jQuery(document).ready((function(){var e=getVideoId();e&&openVideoByHash(e),share_button.length>0&&share_button.forEach((function(e){e.addEventListener("click",shareVideo)}))}));