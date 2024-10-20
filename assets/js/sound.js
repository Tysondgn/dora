// document.addEventListener('DOMContentLoaded', function () {
//     var audio = new Audio('assets/sound/simple-notification.mp3');
//     var soundToggle = document.getElementById('soundToggle');

//     soundToggle.addEventListener('change', function () {
//         if (soundToggle.checked) {
//             // Play sound
//             audio.play();
//         } else {
//             // Pause or stop the sound if needed
//             audio.pause();
//             audio.currentTime = 0;
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    var audio = new Audio('assets/sound/simple-notification.mp3');
    audio.play(); //New
    // function playAudio() {
    //     var playPromise = audio.play();

    //     if (playPromise !== undefined) {
    //         playPromise.then(_ => {
    //             // Autoplay started!
    //         }).catch(error => {
    //             // Autoplay was prevented, show a popup asking for permission
    //             showPermissionPopup();
    //         });
    //     }
    // }

    // function showPermissionPopup() {
    //     var popup = confirm("This website wants to play a notification sound. Do you allow?");
    //     if (popup) {
    //         // User allowed, try playing audio again
    //         playAudio();
    //     } else {
    //         // User denied permission
    //         console.log("Audio permission denied by the user");
    //     }
    // }

    // document.getElementById('soundToggle').addEventListener('change', function () {
    //     if (this.checked) {
    //         // Try playing audio when the soundToggle is checked
    //         playAudio();
    //     }
    // });
});
