let isDragging = false;
let startX = 0;
let startPieceX = 0;
let currentX = 0;
let targetX = 0;

const scene = document.getElementById("scene");
const hole = document.getElementById("hole");
const piece = document.getElementById("piece");
const handle = document.getElementById("handle");
const status = document.getElementById("status");

function initCaptcha() {
    isDragging = false;
    currentX = 0;

    scene.classList.remove("solved");
    status.textContent = "";
    piece.style.transform = "translateX(0px)";
    handle.style.transform = "translateX(0px)";

    const randomImg = IMAGES[Math.floor(Math.random() * IMAGES.length)];
    scene.style.backgroundImage = `url(${randomImg})`;
    piece.style.backgroundImage = `url(${randomImg})`;

    targetX = 100 + Math.floor(Math.random() * (600 - 60 - 100));

    hole.style.top = `${HOLE_Y}px`;
    hole.style.left = `${targetX}px`;

    piece.style.top = `${HOLE_Y}px`;
    piece.style.left = "0px";
    piece.style.backgroundPosition = `-${targetX}px -${HOLE_Y}px`;
}

handle.addEventListener("pointerdown", (e) => {
    if (scene.classList.contains("solved")) return;
    isDragging = true;
    startX = e.clientX;
    startPieceX = currentX;
    handle.setPointerCapture(e.pointerId);
});

handle.addEventListener("pointermove", (e) => {
    if (!isDragging) return;
    let dx = e.clientX - startX;
    currentX = Math.max(0, Math.min(startPieceX + dx, 600 - 60));

    piece.style.transform = `translateX(${currentX}px)`;
    handle.style.transform = `translateX(${currentX}px)`;
});

handle.addEventListener("pointerup", (e) => {
    if (!isDragging) return;
    isDragging = false;
    handle.releasePointerCapture(e.pointerId);

    if (Math.abs(currentX - targetX) <= TOLERANCE) {
        scene.classList.add("solved");
        status.textContent = "Success!";
        piece.style.transform = `translateX(${targetX}px)`;
        handle.style.transform = `translateX(${targetX}px)`;
        setTimeout(initCaptcha, 1500);
    }
});

initCaptcha();
