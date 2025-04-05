$(document).ready(function() {
        
    $(document).on("blur", ".auto-save", function() {
        const $field = $(this);
        const fieldName = $field.attr("name");
        const value = $field.val().trim();
        
        if ((fieldName === 'qualifications[]' || fieldName === 'experiences[]') && value === "") {
            $field.closest('.input-group').remove();
        }
        
        if (fieldName === 'qualifications[]') {
            saveQualifications();
        } else if (fieldName === 'experiences[]') {
            saveExperiences();
        } else {
            saveField(fieldName, value);
        }
    });
    $("#addQualification").click(function() {
        const newField = $(`
            <div class="input-group">
                <input type="text" name="qualifications[]" placeholder="Enter Qualification" class="auto-save">
            </div>
        `);
        newField.insertBefore(this);
        newField.find('input').focus();
    });
    $("#addExperience").click(function() {
        const newField = $(`
            <div class="input-group">
                <input type="text" name="experiences[]" placeholder="Enter Experience" class="auto-save">
            </div>
        `);
        newField.insertBefore(this);
        newField.find('input').focus();
    });
    $("#profilePic").click(function() {
        $("#profilePicUpload").trigger('click');
    });

    $("#profilePicUpload").change(function() {
        if (this.files && this.files[0]) {
            const formData = new FormData();
            formData.append('profile_pic', this.files[0]);
            
            $.ajax({
                url: 'index.php?route=profile',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        $('#profilePic').attr('src', res.newPath + '?' + new Date().getTime());
                    } else {
                        alert(res.message || "Upload failed");
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.statusText);
                }
            });
        }
    });
});

function saveField(field, value) {
    $.ajax({
        url: 'index.php?route=profile',
        type: 'POST',
        data: { field, value },
        success: function(response) {
            console.log("Saved:", field, response);
        },
        error: function(xhr) {
            console.error("Error saving", field, xhr.statusText);
        }
    });
}

function saveQualifications() {
    const qualifications = [];
    $("input[name='qualifications[]']").each(function() {
        const val = $(this).val().trim();
        if (val) qualifications.push(val);
    });
    saveField("qualifications", JSON.stringify(qualifications));
}

function saveExperiences() {
    const experiences = [];
    $("input[name='experiences[]']").each(function() {
        const val = $(this).val().trim();
        if (val) experiences.push(val);
    });
    saveField("experiences", JSON.stringify(experiences));
}

function editDOB(element) {
    const input = element.nextElementSibling;
    element.style.display = "none";
    input.style.display = "inline-block";
    input.focus();
}

function handleKeyPress(event, input) {
    if (event.key === "Enter" || event.key === "Tab") {
        event.preventDefault();
        input.blur();
    }
}