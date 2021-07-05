const btn_play = document.getElementById("btn-play");
const video_tribute = document.getElementById("tribute-home-video");
const formCreateName = "form#form-create-tribute";
const formCreate = document.querySelector(formCreateName);
const slug = document.getElementById("slug");

const SESSION_OBJ_INVITES = "polen_tributes_invites";

function playVideo(evt) {
	if (video_tribute.paused) {
		btn_play.classList.add("hidden");
		video_tribute.play();
	} else {
		btn_play.classList.remove("hidden");
		video_tribute.pause();
	}
}

function slugValidate(valid, message) {
	const slug_message = document.getElementById("slug-message");
	slug.classList.remove("error");
	slug_message.classList.remove("error");
	slug_message.innerText = "";
	if (!valid) {
		slug.classList.add("error");
		slug_message.classList.add("error");
	}
	slug_message.innerText = message;
}

function checkSlug() {
	polSpinner(CONSTANTS.SHOW, ".slug-wrap");
	jQuery.ajax({
		type: "POST",
		url: polenObj.ajax_url,
		data: {
			action: "check_tribute_slug_exists",
			slug: slug.value,
		},
		success: function (response) {
			if (response.success) {
				slugValidate(true, response.data);
			} else {
				slugValidate(false, response.data);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			slugValidate(false, jqXHR.responseJSON.data);
		},
		complete: function () {
			polSpinner(CONSTANTS.HIDDEN);
		},
	});
}

function createTribute(evt) {
	evt.preventDefault();
	if (slug.classList.contains("error")) {
		polError("É preciso uma URL válida para seu Tributo");
		return;
	}
	polSpinner();
	jQuery
		.post(
			polenObj.ajax_url,
			jQuery(formCreateName).serialize(),
			function (result) {
				if (result.success) {
					setSessionMessage(
						CONSTANTS.SUCCESS,
						"Tributo criado",
						"Agora convide seus amigos para essa homenagem"
					);
					window.location.href = result.data.url_redirect;
				} else {
					polError(result.data);
				}
			}
		)
		.fail(function (e) {
			polSpinner(CONSTANTS.HIDDEN);
			if (e.responseJSON) {
				polError(e.responseJSON.data);
			} else {
				polError(e.statusText);
			}
		});
}

if (btn_play) {
	btn_play.addEventListener("click", playVideo);
}

if (slug) {
	slug.addEventListener("focusout", checkSlug);
}

if (formCreate) {
	formCreate.addEventListener("submit", createTribute);
}

if (document.getElementById("invite-friends")) {
	function saveToDisk(obj) {
		sessionStorage.setItem(SESSION_OBJ_INVITES, JSON.stringify(obj));
	}

	function getToDisk() {
		const st = sessionStorage.getItem(SESSION_OBJ_INVITES);
		return st ? JSON.parse(st) : [];
	}

	const inviteFriends = new Vue({
		el: "#invite-friends",
		data: {
			name: "",
			email: "",
			friends: getToDisk(),
		},
		methods: {
			resetAddFriend: function () {
				this.name = this.email = "";
			},
			updateDisk: function () {
				saveToDisk(this.friends);
			},
			addFriend: function () {
				this.friends.push({ name: this.name, email: this.email });
				this.resetAddFriend();
				this.updateDisk();
			},
			removeFriend: function (email) {
				this.friends = this.friends.filter(
					(friend) => friend.email != email
				);
				this.updateDisk();
			},
			sendFriends: function () {
				const formName = "form#friends-form";
				polSpinner();
				jQuery
					.post(
						polenObj.ajax_url,
						jQuery(formName).serialize(),
						function (result) {
							if (result.success) {
								sessionStorage.removeItem(SESSION_OBJ_INVITES);
								polMessage(
									"Amigos adicionados",
									"Amigos adicionados com sucesso"
								);
								this.friends = [];
							} else {
								polError(result.data);
							}
						}
					)
					.fail(function (e) {
						if (e.responseJSON) {
							polError(e.responseJSON.data);
						} else {
							polError(e.statusText);
						}
					})
					.complete(function (e) {
						polSpinner(CONSTANTS.HIDDEN);
					});
			},
		},
	});
}
