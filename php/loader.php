<style>
#page-loader {
    -webkit-animation: hideLoader 0.5s ease 2s forwards;
    animation: hideLoader 0.5s ease 2s forwards;
}
#page-container {
    -webkit-animation: showContent 0s ease 2.1s forwards;
    animation: showContent 0s ease 2.1s forwards;
    opacity: 0;
}
@-webkit-keyframes hideLoader {
    0%   { opacity: 1; visibility: visible; }
    100% { opacity: 0; visibility: hidden; pointer-events: none; }
}
@keyframes hideLoader {
    0%   { opacity: 1; visibility: visible; }
    100% { opacity: 0; visibility: hidden; pointer-events: none; }
}
@-webkit-keyframes showContent {
    0%   { opacity: 0; }
    100% { opacity: 1; }
}
@keyframes showContent {
    0%   { opacity: 0; }
    100% { opacity: 1; }
}
</style>
<div id="page-loader" class="fade show"><span class="spinner"></span></div>