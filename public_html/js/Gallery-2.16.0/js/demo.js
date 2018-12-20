/*
 * blueimp Gallery Demo JS 2.12.1
 * https://github.com/blueimp/Gallery
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global blueimp, $ */

$(function () {
    'use strict';
    $.ajax({
        url: 'https://api.flickr.com/services/rest/',
        data: {
            format: 'json',
            method: 'flickr.interestingness.getList',
            api_key: '7617adae70159d09ba78cfec73c13be3'
        },
        dataType: 'jsonp',
        jsonp: 'jsoncallback'
    }).done(function (result) {
        var carouselLinks = [],
            linksContainer = $('#links'),
            baseUrl;
            $.each(result.photos.photo, function (index, photo) {
                baseUrl = 'https://farm' + photo.farm + '.static.flickr.com/' +
                    photo.server + '/' + photo.id + '_' + photo.secret;
                $('<a/>')
                    .append($('<img>').prop('src', baseUrl + '_s.jpg'))
                    .prop('href', baseUrl + '_b.jpg')
                    .prop('title', photo.title)
                    .attr('data-gallery', '')
                    .appendTo(linksContainer);
                carouselLinks.push({
                    href: baseUrl + '_c.jpg',
                    title: photo.title
                });
            });
    });

});
