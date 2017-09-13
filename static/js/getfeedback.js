$(document).ready(function() {
    
    resetForm("form-1");
    resetForm("form-2");
    resetForm("form-3");

    var _selectListSem = $("#list-sem"),
        _selectListDept = $("#list-dept"),
        _selectListSubject = $("#list-subject"),
        _selectListTeacher = $("#list-teacher");

    _selectListSem.select2();
    _selectListDept.select2({
        minimumResultsForSearch: Infinity
    });
    
    _selectListSubject.select2();
    _selectListTeacher.select2();

    $("select.select-op").select2({
        minimumResultsForSearch: Infinity
    });
    $("select.fp-point-scale").select2();

    _selectListSem.change( function () {

        var _this = $(this);

        _selectListSubject.empty();
        _selectListTeacher.empty();

        _selectListSubject.append($("<option/>", {value: 0, text: '--Select--'}));
        _selectListTeacher.append($("<option/>", {value: 0, text: '--Select--'}));

        _selectListDept.val('0');
        _selectListSubject.val('0');
        _selectListTeacher.val('0');

        _selectListSubject.prop('disabled', true);
        _selectListTeacher.prop('disabled', true);

        _selectListSubject.removeClass("active-select-menu");
        _selectListTeacher.removeClass("active-select-menu");

        if(_this.val() === '0') {

            _selectListDept.prop('disabled', true);

            _selectListDept.removeClass("active-select-menu");

        } else {

            _selectListDept.prop('disabled', false);

            _selectListDept.addClass("active-select-menu");

            if(_this.hasClass("field-error")) {
                _this.removeClass("field-error")
            }

        }

        _selectListDept.select2();

    });

    _selectListDept.change( function () {

        var _this = $(this);

        _selectListSubject.empty();
        _selectListSubject.append($("<option/>", {value: 0, text: '--Select--'}));

        _selectListTeacher.empty();
        _selectListTeacher.append($("<option/>", {value: 0, text: '--Select--'}));
        _selectListTeacher.val('0');
        _selectListTeacher.prop('disabled', true);
        _selectListTeacher.removeClass("active-select-menu");

        if(_this.val() === '0') {

            _selectListSubject.val('0');
            _selectListSubject.prop('disabled', true);
            _selectListSubject.removeClass("active-select-menu");

        } else {

            $.ajax({
                url: "templates/ajax/subject.php",
                method: 'GET',
                data: {field: 'subject', semester: _selectListSem.val(), department: _this.val()},
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                success: function (data) {

                    _selectListSubject.prop('disabled', false);
                    _selectListSubject.addClass("active-select-menu");

                    $.each(data, function (key, elt) {

                        //_selectListSubject.append("<option value='" + key + "'>" + elt + " (" + key + ")</option>");
                        _selectListSubject.append("<option value='" + key + "'>" + elt + "</option>");

                    });

                    if(_this.hasClass("field-error")) {
                        _this.removeClass("field-error")
                    }

                    _selectListSubject.select2();

                },

                error: function (jqXHR, textStatus, errorThrown) {
                    alert('ERROR:' + textStatus);
                }

            });

        }

    });

    _selectListSubject.change( function () {

        var _this = $(this);

        _selectListTeacher.empty();
        _selectListTeacher.append($("<option/>", {value: 0, text: '--Select--'}));

        if(_this.val() === '0') {

            _selectListTeacher.val('0');

            _selectListTeacher.prop('disabled', true);

            _selectListTeacher.removeClass("active-select-menu");

            //_selectListTeacher.siblings("span").removeClass("active-caret");

        } else {

            var _subject = _this.val(),
                _dept = _selectListDept.val(),
                _sem = _selectListSem.val();

            $.ajax({
                url: "templates/ajax/teacher.php",
                method: 'GET',
                data: {field: 'teacher', subject: _subject, dept:_dept, sem:_sem},
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                success: function (data) {

                    _selectListTeacher.prop('disabled', false);
                    _selectListTeacher.addClass("active-select-menu");

                    $.each(data, function (key, elt) {

                        _selectListTeacher.append("<option value='" + elt + "'>" + elt + "</option>");

                    });

                    if(_this.hasClass("field-error")) {
                        _this.removeClass("field-error")
                    }

                    _selectListTeacher.select2();

                },

                error: function (jqXHR, textStatus, errorThrown) {
                    alert('ERROR:' + textStatus);
                }

            });

        }

    });

    _selectListTeacher.change(function () {
        if($(this).val() != '0') {

            if($(this).hasClass("field-error")) {
                $(this).removeClass("field-error")
            }

        }
    });

    function validateSelectList(_selectList) {

        var _fieldEmpty = false;

        _selectList.each( function (i, elt) {

            if($(elt).val() == '0') {

                _fieldEmpty = true;
                $(elt).addClass("field-error");

            } else if($(elt).hasClass("field-error")) {

                $(elt).removeClass("field-error");

            }

        });

        return _fieldEmpty;

    }

    $("li.fp-tab").click( function () {

        //redirect to the appropriate form on tab click
        redirectTo($(this).attr("aria-controls"));

    });

    function redirectTo(formId) {

        $(".fp-form[aria-expanded='true']").attr("aria-expanded", false)
                                            .removeClass("active-form");
        $("#" + formId).attr("aria-expanded", 'true')
                        .addClass("active-form");
        //set tab active corresponding to the form
        setTabActive($(".fp-tab[aria-controls='"  + formId +  "']").attr("id"));

    }

    function setTabActive(tabId) {

        $(".fp-tab[aria-expanded='true']").attr("aria-expanded", false)
                                                .removeClass("active-tab");
        $("#" + tabId).attr("aria-expanded", 'true')
                        .addClass("active-tab");

    }

    function resetForm(_formId) {

        document.getElementById(_formId).reset();

        $("#" + _formId + " select").select2();

    }

    $(window).resize(function () {

    });

    $("li.fp-tab").click( function () {

        //redirect to the appropriate form on tab click
        redirectTo($(this).attr("aria-controls"));

    });

    $("#fp-details-edit button").click( function () {

        $(this).prop("disabled", true);

        $("li#fp-tab-1 span").removeClass("show-span");
        $("form#form-1 select").prop("disabled", false);

        _selectListSem.siblings("span").addClass("active-caret");
        _selectListDept.siblings("span").addClass("active-caret");
        _selectListSubject.siblings("span").addClass("active-caret");
        _selectListTeacher.siblings("span").addClass("active-caret");

        $("li#fp-tab-2 span").removeClass("show-span");
        resetForm("form-2");
        $("#form-2 select").prop("disabled", false);
        $("#btn-form-2-submit").prop("disabled", true);

        resetForm("form-3");
        $("li#fp-tab-3 span").removeClass("show-span");
        $("#btn-form-3-submit").prop("disabled", true);

    });

    $("#fp-error-msg span").click( function () {

        $("#fp-error-msg").removeClass("form-error");

    });

    $("form#form-1").submit(function (e) {

        var _this = $(this),
            _formData = _this.serialize();
            _showErrorMsgId = $("#fp-error-msg"),
            _showErrorMsgH5 = $("#fp-error-msg h5");

        e.preventDefault(e);

        $(document).scrollTop('0');

        $.ajax({

            url: "templates/validate.forms.feedback.php",
            method: "POST",
            data: _formData,
            dataType: "json",
            success: function (data) {

                var _selectListError = validateSelectList($("form#form-1 select"));

                if(data.form === 'form-1' && data.form1 && !_selectListError) {

                    if(_showErrorMsgId.hasClass("form-error")) {

                        _showErrorMsgId.removeClass("form-error");

                    }

                    $("li#fp-tab-1 span").addClass("show-span");

                    resetForm("form-2");
                    //$("#form-2 input, #btn-form-2-submit").prop("disabled", false);
                    $("#btn-form-2-submit").prop("disabled", false);
                    redirectTo("fp-form-2");

                    $("form#form-1 select").prop("disabled", true);
                    $("#fp-details-edit").addClass("show-edit");
                    $("#fp-details-edit button").prop("disabled", false);

                } else if(_selectListError) {

                    _showErrorMsgId.addClass("form-error");
                    _showErrorMsgH5.html("please select all the field(s)");

                } else {

                    alert("Error in form submitting, try again...");
                    resetForm("form-1");

                }

            }

        });

    });

    $("form#form-2").submit(function (e) {

        var _this = $(this),
            _formData = _this.serialize(),
            _showErrorMsgId = $("#fp-error-msg"),
            _showErrorMsgH5 = $("#fp-error-msg h5");

        e.preventDefault(e);

        $(document).scrollTop('0');

        $.ajax({

            url: "templates/validate.forms.feedback.php",
            method: "POST",
            data: _formData,
            dataType: "json",
            success: function (data) {

                var _selectListGeneralError = validateSelectList($("form#form-2 select.select-op"));
                var _selectListPointScaleError = validateSelectList($("form#form-2 select.fp-point-scale"));
                var _selectListError = false;

                if(_selectListGeneralError || _selectListPointScaleError) {

                    _selectListError = true;

                }

                if(data.form === 'form-2' && data.form2 && !_selectListError) {

                    if(_showErrorMsgId.hasClass("form-error")) {

                        _showErrorMsgId.removeClass("form-error");

                    }

                    resetForm("form-3");
                    $("#btn-form-3-submit").prop("disabled", false);
                    redirectTo("fp-form-3");

                    $("li#fp-tab-2 span").addClass("show-span");
                    $("form#form-2 select").prop("disabled", true);
                    $("#btn-form-2-submit").prop("disabled", true);

                } else if(_selectListError) {

                    _showErrorMsgId.addClass("form-error");
                    _showErrorMsgH5.html("please select all the field(s)");

                } else {

                    alert("Error in form submitting, try again...");
                    resetForm("form-2");

                }

            }

        });

    });

    $("form#form-3").submit(function (e) {

        var _this = $(this),
            _formData = _this.serialize(),
            _showErrorMsgId = $("#fp-error-msg"),
            _showErrorMsgH5 = $("#fp-error-msg h5");

        e.preventDefault(e);

        $(document).scrollTop('0');

        $.ajax({

            url: "templates/validate.forms.feedback.php",
            method: "POST",
            data: _formData,
            dataType: "json",
            success: function (data) {

                var _selectListGeneralError = validateSelectList($("form#form-3 select.select-op"));
                var _selectListPointScaleError = validateSelectList($("form#form-3 select.fp-point-scale"));
                var _selectListError = false;

                if(_selectListGeneralError || _selectListPointScaleError) {

                    _selectListError = true;

                }

                if(data.form === 'form-3' && data.form3 && !_selectListError) {

                    if(_showErrorMsgId.hasClass("form-error")) {

                        _showErrorMsgId.removeClass("form-error");

                    }

                    window.setTimeout( function () {

                        $.ajax({
                            url: "templates/forms.status.php",
                            method: 'GET',
                            dataType: 'json',
                            contentType: "application/json;charset=utf-8",
                            success: function(response) {

                                if(response.form1 && response.form2 && response.form3) {

                                    $("li#fp-tab-3 span").addClass("show-span");
                                    window.location = "feedback-success";

                                }

                            }

                        });

                    }, 200);

                    $("li#fp-tab-3 span").addClass("show-span");

                    resetForm("form-3");
                    $("#btn-form-3-submit").prop("disabled", false);
                    redirectTo("fp-form-3");

                    $("form#form-2 select").prop("disabled", true);
                    $("#btn-form-2-submit").prop("disabled", true);

                } else if(_selectListError) {

                    _showErrorMsgId.addClass("form-error");
                    _showErrorMsgH5.html("please select all the field(s)");

                } else {

                    alert("Error in form submitting, try again...");
                    resetForm("form-2");

                }

            }

        });

    });

    $("#form-2 select.select-op").change( function () {

        var _this = $(this),
            _optionSelected = _this.val(),
            _selectedList = _this.attr("name"),
            _pointScaleSelectList = $("#form-2 select.fp-point-scale[name='" + _selectedList + "_point']"),
            _pointScaleSelectListOptGroup = $("#form-2 select.fp-point-scale[name='" + _selectedList + "_point'] optgroup"),
            _pointScaleSelectListOptGroupOption = $("#form-2 select.fp-point-scale[name='" + _selectedList + "_point'] optgroup option"),
            _pointScaleSelectedOptGroup = _pointScaleSelectListOptGroup.filter("[label='" + _optionSelected + "']"),
            _pointScaleSelectedOptGroupIndex = _pointScaleSelectedOptGroup.index();

        //_pointScaleSelectListOptGroupOption.empty();

        //alert(_optionSelected);

        _pointScaleSelectListOptGroupOption.detach();
        handlePointScaleOption(_pointScaleSelectListOptGroup, _pointScaleSelectedOptGroupIndex);

        _pointScaleSelectList.select2();

    });

    $("#form-3 select.select-op").change( function () {

        var _this = $(this),
            _optionSelected = _this.val(),
            _selectedList = _this.attr("name"),
            _pointScaleSelectList = $("#form-3 select.fp-point-scale[name='" + _selectedList + "_point']"),
            _pointScaleSelectListOptGroup = $("#form-3 select.fp-point-scale[name='" + _selectedList + "_point'] optgroup"),
            _pointScaleSelectListOptGroupOption = $("#form-3 select.fp-point-scale[name='" + _selectedList + "_point'] optgroup option"),
            _pointScaleSelectedOptGroup = _pointScaleSelectListOptGroup.filter("[label='" + _optionSelected + "']"),
            _pointScaleSelectedOptGroupIndex = _pointScaleSelectedOptGroup.index();

        //_pointScaleSelectListOptGroupOption.empty();

        _pointScaleSelectListOptGroupOption.detach();
        handlePointScaleOption(_pointScaleSelectListOptGroup, _pointScaleSelectedOptGroupIndex);

        _pointScaleSelectList.select2();

    });

    function handlePointScaleOption (_pointScaleSelectListOptGroup, _pointScaleSelectedOptGroupIndex) {

        var PointScaleOptions = {
            'OptGroup1' : ["<option value='10' disabled='disabled'>10</option>", "<option value='9' disabled='disabled'>9</option>", "<option value='8' disabled='disabled'>8</option>"],
            'OptGroup2' : ["<option value='7' disabled='disabled'>7</option>", "<option value='6' disabled='disabled'>6</option>", "<option value='5' disabled='disabled'>5</option>"],
            'OptGroup3' : ["<option value='4' disabled='disabled'>4</option>", "<option value='3' disabled='disabled'>less than 3</option>"]
        }

        switch(_pointScaleSelectedOptGroupIndex) {

            case 1:
                PointScaleOptions.OptGroup1 = ["<option value='10'>10</option>", "<option value='9'>9</option>", "<option value='8'>8</option>"];
                break;
            case 2:
                PointScaleOptions.OptGroup2 = ["<option value='7'>7</option>", "<option value='6'>6</option>", "<option value='5'>5</option>"];
                break;
            case 3:
                PointScaleOptions.OptGroup3 = ["<option value='4'>4</option>", "<option value='3'>less than 3</option>"];
                break;
        }

        _pointScaleSelectListOptGroup.each(function() {

            var _this = $(this),
                _index = _this.index(),
                _optgroup = "OptGroup" + _index;

            _this.append(PointScaleOptions[_optgroup]);

        });

    }

});