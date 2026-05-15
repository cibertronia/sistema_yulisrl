<style>
#page-loader {
    -webkit-animation: hideLoader 0.5s ease 2s forwards;
    animation: hideLoader 0.5s ease 2s forwards;
}
@-webkit-keyframes hideLoader {
    0%   { opacity: 1; visibility: visible; }
    100% { opacity: 0; visibility: hidden; pointer-events: none; }
}
@keyframes hideLoader {
    0%   { opacity: 1; visibility: visible; }
    100% { opacity: 0; visibility: hidden; pointer-events: none; }
}
</style>
<div id="page-loader" class="fade show"><span class="spinner"></span></div>