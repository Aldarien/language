<?php
function translate($phrase, $default = null) {
  return App\Facade\Language::translate($phrase, $default);
}
function t($phrase, $default = null) {
  return translate($phrase, $default);
}
