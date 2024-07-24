App.Post = {
    isAdmin: 0,
    countIds: [],

    initializePostValidations: function () {
        $("#post_create_form").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                author: {
                    required: true,
                    maxlength: 255
                },
                content: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "The title field is required.",
                    maxlength: "The title may not be greater than 255 characters."
                },
                author: {
                    required: "The title field is required.",
                    maxlength: "The title may not be greater than 255 characters."
                },
                content: {
                    required: "The content field is required."
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            invalidHandler: function(event, validator) {
                // Add a class to the form when there are validation errors
                $("#post_create_form").addClass("has-errors");
            }
        });
    },
    removeFilters: function () {
        $("#title").val("");
        $("#author_name").val("");
        $("#status").val('').trigger('change')
        $("#created_at").val("");
        App.Helpers.removeAllfilters();
    },
    removeSelectionFilters: function () {
        $("#name").val("");
        $("#username").val('').trigger('change');
        $("#phone").val('').trigger('change');
        $("#country").val('').trigger('change');
        $("#subscription_status").val("");
        $("#status").val("").trigger('change');
        $("#created_at").val("");
        App.Helpers.oTable.draw();
    },
    initializeDataTable: function () {
        let table_name = "posts_table";
        let url = App.Helpers.generateApiURL(App.Constants.endPoints.getPosts);
        let sortColumn = [[3, "desc"]];
        let columns = [
            {data: 'show', name: 'show', orderable: false, searchable: false, className: 'show'},
            {data: "id", name: "id", orderable: true, searchable: true},
            {data: "title", name: "title", orderable: true, searchable: true},
            {data: "author", name: "author", orderable: true, searchable: true},
            {data: "status", name: "status", orderable: true, searchable: true},
            {data: "created_at", name: "created_at", orderable: true, searchable: true},
            {data: "updated_at", name: "updated_at", orderable: true, searchable: true},
        ];
        let postData = function (d) {
            d.id = $("#id").val();
            d.title = $("#title").val();
            d.author_name = $("#author_name").val();
            d.status = $("#status").val();
            d.created_at = $("#created_at").val();
        };

        let orderColumn = sortColumn;
        let searchEnabled = true;
        App.Helpers.CreateDataTableIns(table_name, url, columns, postData, searchEnabled, orderColumn, [], true);
    },
    createFormBinding: function (id) {
        $("#create-post").bind("click", function (e) {
            App.Post.initializePostValidations();
            if (!$(this).valid()) {
                App.Post.initializePostValidations();
                event.preventDefault();
                // Optionally, add custom behavior when the form is invalid
                alert("Please fix the errors before submitting.");
            } else {
                // Remove the class if form is valid
                $(this).removeClass("has-errors");
                let url = App.Helpers.generateApiURL(
                    App.Constants.endPoints.createPost
                );
                let onSuccess= function (data) {
                    console.log('success: ', data)
                    if(data.type == "success") {
                        window.location.href = '/post';
                        App.Helpers.showSuccessMessage( data.message );
                    }
                };
                let requestData = $("#post_create_form").serialize();
                App.Ajax.post(url, requestData, onSuccess, false, {});
            }
            // if ($("#post_create_form").valid()) {
        });
    },
    editFormBinding: function (id) {
        $("#create-post").bind("click", function (e) {
            App.Post.initializePostValidations();
            if (!$(this).valid()) {
                App.Post.initializePostValidations();
                event.preventDefault();
                // Optionally, add custom behavior when the form is invalid
                alert("Please fix the errors before submitting.");
            } else {
                // Remove the class if form is valid
                $(this).removeClass("has-errors");
                let url = App.Helpers.generateApiURL(
                    App.Constants.endPoints.editPost
                )+"/"+id;
                let onSuccess= function (data) {
                    console.log('success: ', data)
                    if(data.type == "success") {
                        window.location.href = '/post';
                        App.Helpers.showSuccessMessage( data.message );
                    }
                };
                let requestData = $("#post_create_form").serialize();
                App.Ajax.post(url, requestData, onSuccess, false, {});
            }
            // if ($("#post_create_form").valid()) {
        });
    },
    updateCustomerStatus: function (thisKey, customerId) {
        var customerStatusValue = $(thisKey).find(':selected').text();
        let action = function (isConfirm) {
            if (isConfirm) {
                var customerStatus = $(thisKey).val();
                let onSuccess = function (response) {

                };
                let requestData = {'customer_status': customerStatus, 'customer_id': customerId};
                let url = App.Helpers.generateApiURL(App.Constants.endPoints.updateCustomerStatus);
                App.Ajax.post(url, requestData, onSuccess, false, '', 0);
            }
        };

        App.Helpers.confirm('You want to mark selected customer as ' + customerStatusValue.toLowerCase() + '.', action);

    },
};
