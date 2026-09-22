function updateFavCount() {
    const count = document.querySelectorAll(".track--fav").length;
    favCount.textContent = count;
}

function saveToStorage() {
    const favTracks = [];
    document.querySelectorAll(".track").forEach((track, index) => {
        if (track.classList.contains("track--fav")) {
            favTracks.push(index);
        }
    });
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(favTracks));
}

function loadFromStorage() {
    const saved = sessionStorage.getItem(STORAGE_KEY);
    if (saved) {
        const favTracks = JSON.parse(saved);
        const tracks = document.querySelectorAll(".track");
        favTracks.forEach((index) => {
            if (tracks[index]) {
                tracks[index].classList.add("track--fav");
            }
        });
    }
    updateFavCount();
}

playlist.addEventListener("click", (e) => {
    const favBtn = e.target.closest(".fav");
    if (favBtn) {
        const track = favBtn.closest(".track");
        if (track) {
            track.classList.toggle("track--fav");
            updateFavCount();
            saveToStorage();
        }
    }
});

loadFromStorage();
