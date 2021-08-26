var is_active_btn = false;
var btn_id = 0;
var old_password = $("#old-password");
var new_password = $("#new-password");
var password_confirmation = $("#password-confirmation");

function set_logout_btn() {
    for (var item of $(".btn-logout")) {
        item.addEventListener("click", logout);
    }
}

function submit_form_password(e) {
    e.preventDefault();
    $.ajax({
        url: "/change-password",
        type: "POST",
        data: {
            user_id,
            password_lama: old_password.val(),
            password_baru: new_password.val(),
            konfirmasi_password: password_confirmation.val(),
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            if (response.result == true) {
                Swal.fire({
                    title: "Berhasil",
                    text: response.message,
                    icon: "success",
                    timer: 1800,
                });
                $("#message").html("");
                $("#modal-password").modal("hide");
            } else {
                var messages = /* html */ `<div class="alert alert-danger" role="alert">`;
                for (const property in response.message) {
                    response.message[property].forEach((message) => {
                        messages += /* html */ `${message} <br>`;
                    });
                }
                messages += /* html */ `</div>`;
                $("#message").html(messages);
            }
        },
    });
    return false;
}

function clear_form_password() {
    $("#message").html("");
    old_password.val("");
    new_password.val("");
    password_confirmation.val("");
    old_password.focus();
}

function answer(choice_id, quiz_id) {
    $("#closer").removeClass("d-none");

    $.ajax({
        url: "/cbt/save-answer/" + quiz_id,
        type: "POST",
        data: {
            id: choice_id,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            if (response.is_active != "yes") {
                window.location.reload();
                window.location.href = "/login";
            }

            if (response.status == "success") {
                $("#choice-" + choice_id).attr("checked");
                Swal.fire({
                    title: "Jawaban telah tersimpan",
                    text: "Lanjutkan!",
                    position: "top-end",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 400,
                });
            } else {
                Swal.fire({
                    title: "Jawaban belum tersimpan",
                    text: "Gagal menyimpan jawaban! Silahkan hubungi operator yang bertugas!",
                    position: "top-end",
                    icon: "error",
                    showConfirmButton: true,
                });
            }
            $("#closer").addClass("d-none");
        },
        error: function () {
            $("#closer").addClass("d-none");
        },
    });
}

function logout(e, el = true) {
    if ((el = true)) {
        e.preventDefault();
    }

    $.ajax({
        url: "/logout",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function () {
            window.location.href = "/login";
        },
        error: function () {
            window.location.href = "/login";
        },
    });
}

function togglePassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function timestamp() {
    $.ajax({
        url: "/live-clock",
        success: function (data) {
            $("#timestamp").html(data);
        },
    });
}

function dataTableConfigs() {
    return {
        paging: true,
        iDisplayLength: 25,
        bProcessing: false,
        aoColumns: [
            {
                bSearchable: true,
                bSortable: true,
                sWidth: "20px",
            },
            {
                bSearchable: true,
                bSortable: true,
            },
            {
                bSearchable: true,
                bSortable: true,
                sWidth: "150px",
            },
            {
                bSearchable: true,
                bSortable: true,
                sWidth: "150px",
            },
            {
                bSearchable: true,
                bSortable: true,
                sWidth: "100px",
            },
            {
                bSearchable: true,
                bSortable: true,
                sWidth: "100px",
            },
        ],
        autoWidth: false,
        responsive: true,
    };
}

function change_question(quiz) {
    $("#question").html(quiz.question);
}

function change_choices(quiz) {
    var choices = ``;
    quiz.choices.forEach((choice) => {
        var checked = false;
        if (choice && quiz.my_choice) {
            if (quiz.my_choice.choice_id == choice.id) {
                checked = true;
            }
        }
        choices += `
            <div class="form-check">
                <div class="radio">
                    <input type="radio" class="form-check-input" name="choice" onchange="answer('${choice.id}', '${quiz.id}')" value="${choice.id}" id="choice-${choice.id}"
            `;
        if (checked === true) {
            choices += `checked`;
        }
        choices += `>`;
        choices += /* html */ `
                <label class="form-check-label" for="choice-${choice.id}">
                    <p>
                        ${choice.value}
                    </p>
                </label>
            </div>
        </div>
        `;
    });
    $("#choices").html(choices);
}

function change_current_number(id, number) {
    $("#current_number").text(number);
    $("#btn-question-" + id).addClass("btn-num-active");
    is_active_btn = true;
    btn_id = id;
    $("#current_quiz_id").val(id);
}

function change_next_btn(id) {
    var nextBtn = $("#btn-question-" + id).next();

    $("#btn-next").attr(
        "onclick",
        `get_question('${nextBtn.data("id")}', '${nextBtn.text().trim()}')`
    );
}

function change_prev_btn(id) {
    var prevBtn = $("#btn-question-" + id).prev();

    $("#btn-prev").attr(
        "onclick",
        `get_question('${prevBtn.data("id")}', ${prevBtn.text().trim()})`
    );

    $("#btn-prev").removeClass("d-none");
}

function check_doubt_checkbox(quiz) {
    if (quiz.my_choice) {
        if (quiz.my_choice.is_doubt == true) {
            $("#btn-doubt").prop("checked", true);
        } else {
            $("#btn-doubt").prop("checked", false);
        }
    } else {
        $("#btn-doubt").prop("checked", false);
    }
}

function save_as_doubt() {
    $("#closer").removeClass("d-none");

    $.ajax({
        url: "/cbt/save-doubt/" + $("#current_quiz_id").val(),
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.is_active != "yes") {
                window.location.reload();
                window.location.href = "/login";
            }

            if (response.status == "success") {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Lanjutkan!",
                    position: "top-end",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 450,
                });
            } else {
                Swal.fire({
                    title: "Menandai sebagai ragu gagal!",
                    text: "Silahkan hubungi operator yang bertugas!",
                    position: "top-end",
                    icon: "error",
                    showConfirmButton: true,
                });
            }
            $("#closer").addClass("d-none");
        },
        error: function () {
            $("#closer").addClass("d-none");
        },
    });
}

function focus_to_question_box() {
    $("#question").focus();
}

function get_question(id, number) {
    if (number == "1") {
        $("#btn-prev").addClass("d-none");
    }

    $.ajax({
        url: "/cbt/get-question/" + id,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            if (res.is_active != "yes") {
                window.location.reload();
                window.location.href = "/login";
            }
            var quiz = res.quiz;

            change_question(quiz);
            change_choices(quiz);
            check_current_number();
            change_current_number(id, number);
            change_next_btn(id);
            check_doubt_checkbox(quiz);
            focus_to_question_box();

            if (number != "1") {
                change_prev_btn(id);
            }
            if ($(".numbers").length == number) {
                $("#btn-next").addClass("d-none");
            } else {
                $("#btn-next").removeClass("d-none");
            }
        },
    });
}

function check_current_number() {
    $(".btn-num-active").removeClass("btn-num-active");
}

function finish_test() {
    if ($("#confirm").val() !== "Saya sudah selesai") {
        Swal.fire({
            title: "Konfirmasi",
            text: "Masukkan teks konfirmasi terlebih dahulu!",
            icon: "warning",
            showConfirmButton: true,
        });
        $("#confirm").focus();
        return false;
    }

    $("#closer").removeClass("d-none");

    $.ajax({
        url: "/cbt/finish-test/" + $("#test_id").val(),
        type: "POST",
        data: {},
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.is_active != "yes") {
                window.location.reload();
                window.location.href = "/login";
            }

            if (response.status == "success") {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Tes berhasil dikirim!",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1000,
                });
                window.location.href = "/cbt";
            } else {
                Swal.fire({
                    title: "Gagal!",
                    text: "Tes gagal dikirim! Silahkan hubungi operator yang bertugas!",
                    icon: "error",
                    showConfirmButton: true,
                });
            }
            $("#closer").addClass("d-none");
        },
        error: function () {
            $("#closer").addClass("d-none");
        },
    });
}

function validate_before_submit() {
    $.ajax({
        url: "/cbt/validate-test/" + $("#test_id").val(),
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            $("#test_desc").val(
                `${res.answered} soal dijawab, ${res.not_answered} soal belum dijawab, ${res.doubted} soal ragu.`
            );
        },
    });
}

function get_all_questions() {
    var uri = window.location.pathname.split("/");

    $.ajax({
        url: "/cbt/get-all-question/" + uri[3],
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            filter_question_and_colorize_it(response);
        },
    });
}

function filter_question_and_colorize_it(quizzes) {
    quizzes.forEach((quiz) => {
        if (quiz.my_answer) {
            remove_all_btn_class(quiz.id);
            $("#btn-question-" + quiz.id).addClass("btn-num-answered");

            if (quiz.my_answer.is_doubt) {
                remove_all_btn_class(quiz.id);
                $("#btn-question-" + quiz.id).addClass("btn-num-doubt");
            }
        }
        if (is_active_btn) {
            remove_all_btn_class(btn_id);
            set_active_btn(btn_id);
        }
    });
}

function init_key() {
    $("body").on("keydown", function (e) {
        if (e.keyCode == 39) {
            $("#btn-next").click();
        } else if (e.keyCode == 37) {
            $("#btn-prev").click();
        }
    });
}

function set_active_btn(id) {
    $("#btn-question-" + id).addClass("btn-num-active");
}

function remove_all_btn_class(id) {
    $("#btn-question-" + id).removeClass("btn-num-answered");
    $("#btn-question-" + id).removeClass("btn-num-active");
    $("#btn-question-" + id).removeClass("btn-num-doubt");
}

function load() {
    setInterval(get_all_questions, 500);
    init_key();
    c = 0;

    $.ajax({
        url: "/cbt/get-time-left/" + $("#current_test_id").val(),
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (res) {
            c = Math.ceil(
                parseInt(res.time_left) -
                    (res.time_continue - res.time_pause * 60)
            );

            start_countdown(c);
        },
    });
}

function start_countdown(c) {
    var t;
    timed_count(c, t);
}

function change_time_left_view(hours_left, minutes_left, seconds_left) {
    $("#hours-left").text(hours_left);
    $("#minutes-left").text(minutes_left);
    $("#seconds-left").text(seconds_left);
}

function timed_count(c, t) {
    var hours = parseInt(c / 3600) % 24;
    var minutes = parseInt(c / 60) % 60;
    var seconds = c % 60;

    var hours_left = hours < 10 ? "0" + hours : hours;
    var minutes_left = minutes < 10 ? "0" + minutes : minutes;
    var seconds_left = seconds < 10 ? "0" + seconds : seconds;

    save_unix_timestamp_left(c);
    change_time_left_view(hours_left, minutes_left, seconds_left);

    if (c == 0) {
        $("#confirm").val("Saya sudah selesai");
        finish_test();
        console.log("SELESAI");
    }

    c = c - 1;
    t = setTimeout(function () {
        timed_count(c, t);
    }, 1000);
}

function save_unix_timestamp_left(time_left) {
    console.log(time_left);
    $.ajax({
        url: "/cbt/save-time-left/" + $("#current_test_id").val(),
        type: "POST",
        data: {
            time_left,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (res) {},
    });
}

$(document).ready(function () {
    $(function () {
        setInterval(timestamp, 1000);
        $("#dataTable").DataTable(dataTableConfigs);
        $("#btn-doubt").on("change", save_as_doubt);
        $("#finish_btn").on("click", finish_test);
        $("#btn-stop").on("click", validate_before_submit);
        $("#show-password").on("mouseup", togglePassword);
        $("#show-password").on("mousedown", togglePassword);
        $("#modal-password").on("shown.bs.modal", clear_form_password);
        $("#show-password").css("cursor", "pointer");
        $("#form-password").submit(submit_form_password);
        $("#dataTable_filter").addClass("float-right");
        $("#dataTable_paginate").addClass("float-right");
        set_logout_btn();
        check_current_number();
        change_current_number($("#current_quiz_id").val(), 1);
        $("#app_mode").val() == "production"
            ? setInterval(console.clear, 5000)
            : null;
    });
});
