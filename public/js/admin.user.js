/*globals jQuery, _ */

var rowTemplate = _.template(
        '<tr data-id="<%= id %>" id="user-<%= id %>">' +
        '<td><input type="checkbox" value="<%= id %>"></td>' +
        '<td><%- email %></td>' +
        '<td><img src="<%- avatar %>" /></td>' +
        '<td><%- hobbies %></td>' +
        '</tr>'),

    UserCollection = (function ($, _) {
        var coll = {
            url: '/admin/user',
            table: '#user-tbody',
            list: []
        };

        coll.add = function (user) {

            // insert into the collection and the page
            coll.list.push(user);
            $(rowTemplate(user)).appendTo(coll.table);

            // make all user rows clickable
            $(coll.table)
                .children('tr')
                .off('click') // remove old event handlers
                .on('click', function (event) {
                    coll.edit($(this).data('id'));
                });

        };

        coll.edit = function (id) {

            var user = _.findWhere(coll.list, {id: id});

            $('#profile-form').attr('action', coll.url + '/' + user.id)
            $('#profile-form input[name="id"]').val(user.id);
            $('#profile-form input[name="email"]').val(user.email);
            $('#user-avatar-img').attr('src', user.avatar);
            $('#profile-form textarea[name="hobbies"]').val(user.hobbies);
            $('#profile-form input[name="password"]').val(null);
            $('#profile-form input[name="password_confirmation"]').val(null);
            if (user.admin) {
                $('#profile-form input[name="admin"]').prop('checked', true);
            } else {
                $('#profile-form input[name="admin"]').prop('checked', false);
            }
            $('#user-edit-modal').modal('show');

        };

        coll.update = function (event) {

            var user = {
                id: parseInt($('#profile-form input[name="id"]').val(), 10),
                email: $('#profile-form input[name="email"]').val(),
                hobbies: $('#profile-form textarea[name="hobbies"]').val(),
                admin: $('#profile-form input[name="admin"]').prop('checked')
            },
                avatar = $('#profile-form input[name="avatar"]').val(),
                $user = _.findWhere(coll.list, {id: user.id});

            if (avatar !== '') {
                user.avatar = avatar;
            }

            // update the collection
            $user = $.extend($user, user);

            // update the page
            $(rowTemplate($user)).replaceAll('#user-' + $user.id);

            // assume this will work
            $('#profile-form').submit();
            $('#user-edit-modal').modal('hide');

        };

        coll.deleteSelected = function () {

            var doomed = $(coll.table + ' input:checkbox:checked');
            _.each(doomed, function (box) {
                coll.delete(box.value);
            });

        };

        coll.delete = function (id) {

            // visual cue to show which row we are working on
            $('#user-' + id).addClass('danger');

            // call the REST api to delete from database
            $.ajax(coll.url + '/' + id, {
                method: 'delete',
                dataType: 'json',
                success: function (data, textStatus) {

                    // if that worked, we remove from the collection and from the page
                    $('#user-' + id).fadeOut(1000, function () {
                        $('#user-' + id).remove();
                    });
                    coll.list = _.reject(coll.list, function (user) {
                        return user.id === id;
                    });

                }
            })
        };

        coll.get = function () {

            // retrieve all the users via ajax (no pagination)
            $.get(coll.url, function (data, textStatus) {

                if (textStatus === 'success') {
                    _.each(data, function (user) {
                        coll.add(user);
                    });
                }

            }, 'json');

        };

        return coll;

    })(jQuery, _);

(function ($) {
    'use strict';

    $(document).ready(function () {

        // jquery will send this header every time it makes a request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        // this fills the table
        UserCollection.get();

        $('#user-edit-modal-save').on('click', UserCollection.update);
        $('#delete-btn').on('click', UserCollection.deleteSelected);

    });

}(jQuery));
