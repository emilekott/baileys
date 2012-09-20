/**
 * @file
 * Handles AJAX fetching of views, including filter submission and response.
 */
(function ($) {

/**
 * Attaches the AJAX behavior to Views exposed filter forms and key View links.
 */
Drupal.behaviors.ViewsAjaxView = {};
Drupal.behaviors.ViewsAjaxView.attach = function() {
  if (Drupal.settings && Drupal.settings.views && Drupal.settings.views.ajaxViews) {
    $.each(Drupal.settings.views.ajaxViews, function(i, settings) {
      Drupal.views.instances[i] = new Drupal.views.ajaxView(settings);
    });

    // Trigger the previous filtered search if the filter form id is in the URL hash.
    if (window.location.hash) {
      hash = Drupal.Views.getLocationHash();
      // Do we have a hash that corresponds to an auto-submit form?
      $exposed_form_submitted = $('#' + hash.expForm);
      if ($exposed_form_submitted.hasClass('ctools-auto-submit-full-form')) {
        $exposed_form_submitted.once(function() {
          if (Drupal.Views.isNumeric(hash.page)) {
            var pageValue = $('<input type="hidden" name="page">').val(hash.page)
            $exposed_form_submitted.prepend(pageValue);
          }
          // @see Drupal.views.ajaxView.prototype.attachExposedFormAjax
          var button = $('input[type=submit], input[type=image]', $exposed_form_submitted);
          button = button[0];
          $(button).click();
        });
      } else if (Drupal.Views.isNumeric(hash.page)) {
        // No filters, but check for page specified in location hash.
        $('ul.pager').once(function () {
          $('.pager-item a').each( function() {
            var args = Drupal.Views.parseQueryString(this.href);
            if (hash.page == args.page) {
              $(this).click();
              return false;
            }
          });
        });
      }
    }// end if hash
  }
};

Drupal.views = {};
Drupal.views.instances = {};

/**
 * Javascript object for a certain view.
 */
Drupal.views.ajaxView = function(settings) {
  var selector = '.view-dom-id-' + settings.view_dom_id;
  this.$view = $(selector);

  // Retrieve the path to use for views' ajax.
  var ajax_path = Drupal.settings.views.ajax_path;

  // If there are multiple views this might've ended up showing up multiple times.
  if (ajax_path.constructor.toString().indexOf("Array") != -1) {
    ajax_path = ajax_path[0];
  }

  // Check if there are any GET parameters to send to views.
  var queryString = window.location.search || '';
  if (queryString !== '') {
    // Remove the question mark and Drupal path component if any.
    var queryString = queryString.slice(1).replace(/q=[^&]+&?|&?render=[^&]+/, '');
    if (queryString !== '') {
      // If there is a '?' in ajax_path, clean url are on and & should be used to add parameters.
      queryString = ((/\?/.test(ajax_path)) ? '&' : '?') + queryString;
    }
  }

  this.element_settings = {
    url: ajax_path + queryString,
    submit: settings,
    setClick: true,
    event: 'click',
    selector: selector,
    progress: { type: 'throbber' }
  };

  this.settings = settings;

  // Add the ajax to exposed forms.
  this.$exposed_form = $('form#views-exposed-form-'+ settings.view_name.replace(/_/g, '-') + '-' + settings.view_display_id.replace(/_/g, '-'));
  this.$exposed_form.once(jQuery.proxy(this.attachExposedFormAjax, this));
  this.$exposed_form.ajaxComplete(jQuery.proxy(this.ajaxCompleteExposedCallback, this));

  // Add the ajax to pagers.
  this.$view
    // Don't attach to nested views. Doing so would attach multiple behaviors
    // to a given element.
    .filter(jQuery.proxy(this.filterNestedViews, this))
    .once(jQuery.proxy(this.attachPagerAjax, this));
};

/**
 * Exposed forms use this to append their id as a URL hash so we can
 * resubmit the form when the browser's "Back" button is used.
 */
Drupal.views.ajaxView.prototype.ajaxCompleteExposedCallback = function(event, request, options) {
  if (options.url === this.element_settings.url) {
    var data = Drupal.Views.parseQueryString(options.data);
    Drupal.Views.updateLocationHash({ expForm: this.$exposed_form.attr('id'), page: data.page });
    this.$exposed_form.find('input[name=page]').remove();
  }
}

Drupal.views.ajaxView.prototype.attachExposedFormAjax = function() {
  var button = $('input[type=submit], input[type=image]', this.$exposed_form);
  button = button[0];

  this.exposedFormAjax = new Drupal.ajax($(button).attr('id'), button, this.element_settings);
};

Drupal.views.ajaxView.prototype.filterNestedViews= function() {
  // If there is at least one parent with a view class, this view
  // is nested (e.g., an attachment). Bail.
  return !this.$view.parents('.view').size();
};

/**
 * Click handler updates the location hash based on pager.
 */
Drupal.views.ajaxView.prototype.pagerHandler = function(event) {
  var args = Drupal.Views.parseQueryString(event.target.href);
  Drupal.Views.updateLocationHash({ page: args.page });
}


/**
 * Attach the ajax behavior to each link.
 */
Drupal.views.ajaxView.prototype.attachPagerAjax = function() {
  this.$view.find('ul.pager > li > a, th.views-field a, .attachment .views-summary a')
  .each(jQuery.proxy(this.attachPagerLinkAjax, this));
};

/**
 * Attach the ajax behavior to a singe link.
 */
Drupal.views.ajaxView.prototype.attachPagerLinkAjax = function(id, link) {
  var $link = $(link);
  var viewData = {};
  var href = $link.attr('href');
  // Construct an object using the settings defaults and then overriding
  // with data specific to the link.
  $.extend(
    viewData,
    this.settings,
    Drupal.Views.parseQueryString(href),
    // Extract argument data from the URL.
    Drupal.Views.parseViewArgs(href, this.settings.view_base_path)
  );

  // For anchor tags, these will go to the target of the anchor rather
  // than the usual location.
  $.extend(viewData, Drupal.Views.parseViewArgs(href, this.settings.view_base_path));

  this.element_settings.submit = viewData;
  this.pagerAjax = new Drupal.ajax(false, $link, this.element_settings);

  //Attach click handler to update hash
  $link.click(this.pagerHandler);
};

Drupal.ajax.prototype.commands.viewsScrollTop = function (ajax, response, status) {
  // Scroll to the top of the view. This will allow users
  // to browse newly loaded content after e.g. clicking a pager
  // link.
  var offset = $(response.selector).offset();
  // We can't guarantee that the scrollable object should be
  // the body, as the view could be embedded in something
  // more complex such as a modal popup. Recurse up the DOM
  // and scroll the first element that has a non-zero top.
  var scrollTarget = response.selector;
  while ($(scrollTarget).scrollTop() == 0 && $(scrollTarget).parent()) {
    scrollTarget = $(scrollTarget).parent();
  }
  // Only scroll upward
  if (offset.top - 10 < $(scrollTarget).scrollTop()) {
    $(scrollTarget).animate({scrollTop: (offset.top - 10)}, 500);
  }
};

})(jQuery);
