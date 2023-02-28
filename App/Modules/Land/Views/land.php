<?php defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); ?>
{% extends Land/Views/layout %}

{% block title %}CORE{% endblock %}

{% block content %}
    <div class="container col-xxl-8 px-4 py-5">
      <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-lg-6">
          <h1 class="display-4 fw-bold lh-1 mb-3">CORE</h1>
          <p class="col-lg-10">Hierarchical Model View Controller</p>
          <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a class="btn btn-primary btn-lg px-4 me-md-2" href="http://yusufmambrasar.id">@yusufmambrasar</a>
          </div>
        </div>
        <div class="col-10 col-sm-8 col-lg-6">
          <img src="{{ SiteUrl('assets/images/land.png') }}" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
        </div>
      </div>
    </div>
{% endblock %}