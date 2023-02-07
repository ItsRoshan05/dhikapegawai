$(document).ready(function () {
    table = $(listPegawai).DataTable({
        processing: !0,
        serverSide: !0,
        order: [],
        ajax: {
            url: `${BASE_URL}/api/data/pegawai`,
            type: "POST",
            dataSrc: function (json) {
                return json.data;
            },
            error: function (error) {
                console.log(error);
            },
        },
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            $(nRow).attr("data-id", aData.id);
        },
        columns: dataColumnTable([
            "id",
            "nama_pegawai",
            "jabatan_kategori",
            "nama_kategori",
            "alamat"
        ]),
        columnDefs: [
            {
                sClass: "text-left",
                targets: [0],
                orderable: true,
                render: function (data, type, row) {
                    return `${row.nama_pegawai}`;
                },
            },
            {
                sClass: "text-left",
                targets: [1],
                orderable: true,
                render: function (data, type, row) {
                    return `${row.alamat}`;
                },
            },
            {
                sClass: "text-left",
                targets: [2],
                orderable: true,
                render: function (data, type, row) {
                    return `${row.nama_kategori}`;
                },
            },
            {
                sClass: "text-center",
                targets: [3],
                orderable: !0,
                render: function (data, type, row) {
                    return (
                        "<button class='btn btn-danger btn-sm' id='delete' data-id=" +
                        row.id +
                        " data-toggle='tooltip' title='Hapus Data'><i class='fas fa-trash-alt'></i></button> \n <button class='btn btn-warning btn-sm' id='edit' data-id=" +
                        row.id +
                        " data-toggle='tooltip' title='Edit Data'><i class='fas fa-pencil-alt'></i></button> \n <button class='btn btn-info btn-sm' id='reset' data-id=" +
                        row.id +
                        " data-toggle='tooltip' title='Reset Password'><i class='fas fa-sync-alt'></i></button>"
                    );
                },
            },
        ],
    });
})