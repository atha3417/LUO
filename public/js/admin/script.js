function toggleActive(id) {
    $.ajax({
        url: "/cbt/admin/manage/users/" + id,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (res) {
            if (res == "not_active") {
                $("#door-" + id).addClass("fa-door-open");
                $("#door-" + id).removeClass("fa-door-closed");
            } else {
                $("#door-" + id).addClass("fa-door-closed");
                $("#door-" + id).removeClass("fa-door-open");
            }
        },
    });
}

function showPassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function logout(e) {
    e.preventDefault();
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

function init_dataTable() {
    $("#dataTable").DataTable({
        pageLength: Infinity,
        paging: false,
    });

    $("#example2")
        .DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        })
        .buttons()
        .container()
        .appendTo("#example2_wrapper .col-md-6:eq(0)");

    $("#example2_wrapper .col-md-6:eq(0)").addClass("col-md-8");
    $("#example2_wrapper .col-md-6:eq(0)").removeClass("col-md-6");

    $("#example2_wrapper .col-md-6:eq(0)").addClass("col-md-4");
    $("#example2_wrapper .col-md-6:eq(0)").removeClass("col-md-6");

    $("#example2_wrapper .col-md-8:eq(0) .dt-buttons").append(
        $("#btn-create").html()
    );
}

function set_logout_btn() {
    for (let item of $(".btn-logout")) {
        item.addEventListener("click", logout);
    }
}

function get_test_detail() {
    $.ajax({
        url: "/cbt/admin/manage/tests/" + $(this).data("id"),
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (res) {
            if (res) {
                $("#test_name").val(res.test_name);
                $("#type").val(res.type.name);
                $("#for").val(res.for);
                $("#start_time").val(res.start_test);
                $("#end_time").val(res.end_test);
                $("#basic_point").val(res.basic_point);
                $("#max_point").val(res.maximal_point);
                $("#duration").val(res.duration);
                $("#created_at").val(
                    moment(res.created_at).format("YYYY-MM-DD h:mm:ss")
                );
                $("#updated_at").val(
                    moment(res.updated_at).format("YYYY-MM-DD h:mm:ss")
                );
                $("#btn-user").data("id", res.id);
            }
        },
    });
}

function get_test_details() {
    $(this).on("click", get_test_detail);
}

function get_user_participants() {
    $.ajax({
        url: "/cbt/admin/manage/tests/" + $(this).data("id"),
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (res) {
            var tbl_participant = ``;
            var no = 1;
            res.participants.forEach((participant) => {
                tbl_participant += `
                    <tr>
                        <th scope="row">${no}</th>
                        <td>${participant.username}</td>
                        <td>${participant.name}</td>
                        <td>${participant.class}</td>
                    </tr>
                `;
                no++;
            });
            $("#tbl-body").html(tbl_participant);
        },
    });
}

function init_select2() {
    $(".select2bs4").select2({
        theme: "bootstrap4",
    });
}

function init_datetime_picker() {
    $("#start-test").datetimepicker({
        icons: { time: "far fa-clock" },
        format: "YYYY-MM-DD h:mm:ss",
    });

    $("#end-test").datetimepicker({
        icons: { time: "far fa-clock" },
        format: "YYYY-MM-DD h:mm:ss",
    });
}

function check_or_not() {
    if (this.checked) {
        $(".participants").each(function () {
            this.checked = true;
        });
    } else {
        $(".participants").each(function () {
            this.checked = false;
        });
    }
}

function check_core_checkbox() {
    if ($(".participants:checked").length == $(".participants").length) {
        $("#select_all").prop("checked", true);
    } else {
        $("#select_all").prop("checked", false);
    }
}

function submit_form_participant() {
    $("#form-participant").submit();
}

$(document).ready(function () {
    $("#show-password").on("change", showPassword);
    $("#submit-form-participant").on("click", submit_form_participant);
    $("#select_all").on("click", check_or_not);
    $(".participants").on("click", check_core_checkbox);
    $(".btn-detail").each(get_test_details);
    $("#btn-user").on("click", get_user_participants);
    $("#th-checkbox").css("cursor", "default");
    check_core_checkbox();
    init_select2();
    init_datetime_picker();
    init_dataTable();
    set_logout_btn();
});
