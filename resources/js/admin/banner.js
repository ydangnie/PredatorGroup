$('#addBannerForm').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    // Lấy token từ thẻ meta trong layout
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: "/admin/banner/store",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken // <--- THÊM DÒNG NÀY
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                $('#addBannerModal').modal('hide');
                location.reload(); // Tải lại trang để thấy banner mới
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            if (errors) {
                let errorMsg = '';
                $.each(errors, function(key, value) {
                    errorMsg += value[0] + '\n';
                });
                alert(errorMsg);
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        }
    });
});