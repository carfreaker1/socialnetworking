document.addEventListener("DOMContentLoaded", () => {
    const avatarUpload = document.querySelector(".avatar-upload");
  
    avatarUpload.addEventListener("click", () => {
      // Only append if modal doesn't already exist
      if (!document.getElementById("imageModal")) {
        const modalHtml = `
          <div id="imageModal" class="modal">
            <div class="dialog">
              <h3>Change Profile Picture</h3>
              <input type="file" id="newProfileImage" accept="image/*" class="input" />
              <div id="imagePreviewContainer" style="margin-top: 10px;">
                <img id="imagePreview" src="" style="max-width: 100%; max-height: 200px; display: none; border-radius: 6px;" />
              </div>
              <div class="actions">
                <button class="btn secondary" id="cancelImageChange">Cancel</button>
                <button class="btn" id="saveProfileImageChange">Save</button>
              </div>
            </div>
          </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
      }
  
      // Show modal
      const modal = document.getElementById("imageModal");
      modal.classList.add("show");
  
      const fileInput = document.getElementById("newProfileImage");
      const previewImg = document.getElementById("imagePreview");
  
      // Clear previous preview
      fileInput.value = '';
      previewImg.style.display = 'none';
      previewImg.src = '';
  
      // Show preview on file select
      fileInput.onchange = () => {
        const file = fileInput.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      };
  
      // Cancel button
      document.getElementById("cancelImageChange").onclick = () => {
        modal.classList.remove("show");
      };
  
      // Save button — apply preview to profile image
      document.getElementById("saveImageChange").onclick = () => {
        const file = fileInput.files[0];
        if (file && previewImg.src) {
          document.getElementById("profilePhoto").src = previewImg.src; // Update avatar image
          modal.classList.remove("show");
        }
      };
    });
  });
  
  
  
  
  document.addEventListener('DOMContentLoaded', () => {
  
      // Profile Edit Modal
      const editModal = document.querySelector('#editModal');
      const openEditBtn = document.querySelector('#openEdit');
      const cancelEditBtn = document.querySelector('#cancelEdit');
      const saveEditBtn = document.querySelector('#saveEditUser');
  
      const nameValue = document.querySelector('#nameValue');
      const ageValue = document.querySelector('#ageValue');
      const displayName = document.querySelector('#displayName');
  
      openEditBtn.addEventListener('click', () => {
          editModal.style.display = 'flex';
          document.querySelector('#editName').value = nameValue.textContent;
          document.querySelector('#editAge').value = ageValue.textContent;
      });
  
      cancelEditBtn.addEventListener('click', () => {
          editModal.style.display = 'none';
      });
  
      saveEditBtn.addEventListener('click', () => {
          const name = document.querySelector('#editName').value.trim();
          const age = document.querySelector('#editAge').value;
  
          if (name) {
              nameValue.textContent = name;
              displayName.textContent = name;
          }
          if (age) {
              ageValue.textContent = age;
          }
          editModal.style.display = 'none';
      });
  
      // Image preview for post
      const postInput = document.querySelector('#postImage');
      const preview = document.querySelector('#postPreview');
      const postDrop = document.querySelector('#postDrop');
  
      postDrop.addEventListener('click', () => postInput.click());
  
      postInput.addEventListener('change', (e) => {
          const file = e.target.files[0];
          if (file) {
              const img = new Image();
              img.src = URL.createObjectURL(file);
              img.style.maxWidth = "100%";
              img.style.borderRadius = "8px";
              preview.innerHTML = '';
              preview.append(img);
          }
      });
  
  });
  function showNotification(message, type) {
      const notification = document.getElementById("notification");
      notification.textContent = message;
      notification.className = type === "success" ? "show" : "show error";
      notification.classList.add("show");
      setTimeout(() => {
          notification.classList.remove("show");
      }, 3000);
  }