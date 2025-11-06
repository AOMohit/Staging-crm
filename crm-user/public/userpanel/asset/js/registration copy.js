
    let currentStep = 1;
    const totalSteps = 3;

    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const previewBtn = document.getElementById('previewBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registrationForm');

    function updateStepIndicator(){
      document.querySelectorAll('.step').forEach((step, idx)=>{
        const n = idx + 1;
        step.classList.remove('active','completed');
        if(n < currentStep) step.classList.add('completed');
        else if(n === currentStep) step.classList.add('active');
      });
    }

    function showStep(n){
      document.querySelectorAll('.form-step').forEach(s=>s.classList.remove('active'));
      requestAnimationFrame(()=>document.getElementById('step'+n).classList.add('active'));
      backBtn.style.display = n === 1 ? 'none' : 'inline-block';
      nextBtn.style.display = n === totalSteps ? 'none' : 'inline-block';
      submitBtn.style.display = n === totalSteps ? 'inline-block' : 'none';
      previewBtn.style.display = n === totalSteps ? 'inline-block' : 'none';
      updateStepIndicator();
      window.scrollTo({top:0,behavior:'smooth'});
    }

    function markInvalid(field){
      field.style.borderColor='var(--primary)';
      field.style.boxShadow='0 0 0 4px rgba(255,178,36,.25)';
      field.addEventListener('input',function(){
        this.style.borderColor='rgba(79,51,37,.12)';
        this.style.boxShadow='none';
      },{once:true});
    }

    function validateStep(n){
      const el = document.getElementById('step'+n);
      const required = el.querySelectorAll('input[required], select[required], textarea[required]');
      for(const field of required){
        if((field.type==='checkbox' && !field.checked) || !String(field.value||'').trim()){
          field.focus(); markInvalid(field); return false;
        }
        if(field.id==='email'){
          const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
          if(!ok){ field.focus(); markInvalid(field); return false; }
        }
      }
      return true;
    }

    nextBtn.addEventListener('click',()=>{
      if(validateStep(currentStep)){ currentStep++; showStep(currentStep); }
    });
    backBtn.addEventListener('click',()=>{ currentStep--; showStep(currentStep); });

    // File upload previews + filename feedback
    document.querySelectorAll('.file-upload input[type="file"]').forEach(input=>{
      input.addEventListener('change', function(){
        const box = this.closest('.file-upload');
        const nameDiv = box.querySelector('.muted');
        const thumb = box.querySelector('.thumb');
        const file = this.files && this.files[0];
        if(!file) return;

        nameDiv.textContent = file.name;
        box.style.borderColor = 'var(--primary)';
        box.style.background = 'rgba(255,178,36,.08)';
        box.style.transform = 'scale(1.01)';
        setTimeout(()=>{ box.style.transform='scale(1)'; },150);

        // simple size guard (20MB)
        if(file.size > 20*1024*1024){
          alert('File exceeds 20MB: ' + file.name);
          this.value='';
          nameDiv.textContent = 'Click to upload or drag & drop';
          if(thumb){ thumb.style.display='none'; thumb.src=''; }
          return;
        }

        // preview only for images
        if(thumb){
          if(file.type.startsWith('image/')){
            const reader = new FileReader();
            reader.onload = e => { thumb.src = e.target.result; thumb.style.display='block'; };
            reader.readAsDataURL(file);
          }else{
            thumb.style.display='none'; thumb.src='';
          }
        }
      });
    });

    // Preview modal logic
    const backdrop = document.getElementById('previewBackdrop');
    const grid = document.getElementById('previewGrid');
    const closePreview = ()=>{ backdrop.style.display='none'; backdrop.setAttribute('aria-hidden','true'); };
    document.getElementById('closePreview').addEventListener('click', closePreview);
    document.getElementById('closePreview2').addEventListener('click', closePreview);

    function buildPreview(){
      grid.innerHTML='';
      const fields = [
        ['First Name', 'firstName'],
        ['Last Name', 'lastName'],
        ['Phone', 'phone'],
        ['Email', 'email'],
        ['Expedition', 'expedition'],
        ['Country', 'country'],
        ['Address', 'address'],
        ['State/Province', 'state'],
        ['City', 'city'],
        ['PIN/ZIP', 'pincode'],
        ['DOB', 'dob'],
        ['Profession', 'profession'],
        ['Blood Group', 'bloodGroup'],
        ['Meal Preference', 'mealPref'],
        ['T-Shirt Size', 'tshirtSize'],
        ['Medical Condition', 'medicalCondition'],
        ['Emergency Name', 'emergencyName'],
        ['Emergency Phone', 'emergencyPhone']
      ];
      for(const [label,id] of fields){
        const val = (document.getElementById(id)?.value || '').toString();
        const item = document.createElement('div');
        item.className='preview-item';
        item.innerHTML = `<div class="muted">${label}</div><div>${val ? val.replace(/</g,'&lt;') : '-'}</div>`;
        grid.appendChild(item);
      }
      const files = [
        ['Passport Front','passport_front'],
        ['Passport Back','passport_back'],
        ['PAN Card','pan_gst'],
        ['Aadhaar Card','adhar_card'],
        ['Driving License','driving'],
        ['GST Certificate','gst_certificate'],
        ['Profile Picture','profile']
      ];
      for(const [label,name] of files){
        let fileName = '-';
        const input = form.querySelector(`input[name="${name}"]`);
        // const fileName = input?.files && input.files[0] ? input.files[0].name : '-';
        const oldInput = form.querySelector(`input[name="old_${name}"]`);
        if (oldInput) {
          fileName = oldInput.value.split('/').pop();
        } else {
          // fallback: agar span me filename hai to vo use karo
          const span = input?.parentElement.querySelector('span');
          if (span) {
            fileName = span.textContent.trim();
          }
        }
        const item = document.createElement('div');
        item.className='preview-item';
        item.innerHTML = `<div class="muted">${label}</div><div>${fileName.replace(/</g,'&lt;')}</div>`;
        grid.appendChild(item);
      }
    }

    previewBtn.addEventListener('click',()=>{
      if(!validateStep(currentStep)) return;
      buildPreview();
      backdrop.style.display='flex';
      backdrop.setAttribute('aria-hidden','false');
    });

    document.getElementById('confirmSubmit').addEventListener('click',()=>{
      backdrop.style.display='none';
      // trigger real submit
      form.requestSubmit();
    });

    form.addEventListener('submit', (e)=>{
      
      e.preventDefault();
      if(!validateStep(currentStep)) return;

      submitBtn.innerHTML = `<span style="display:inline-flex;align-items:center;gap:10px;">
        <div style="width:18px;height:18px;border:2px solid #fff;border-top:2px solid transparent;border-radius:50%;animation:spin 1s linear infinite"></div>
        Submitting...
      </span>`;
      submitBtn.disabled = true;

    // OLD FLOW: AJAX form submit
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json()) // Laravel must return JSON
    .then(data => {
        if (data.success) {
            // NEW FLOW: Show success popup
            setTimeout(() => {
                const success = document.createElement('div');
                success.style.cssText = `
                position:fixed;
                top:50%;
                left:50%;
                transform:translate(-50%,-50%);
                background:rgba(255,255,255,.95);
                backdrop-filter:blur(25px);
                padding:28px;
                border-radius:18px;
                box-shadow:0 25px 70px rgba(79,51,37,.2);
                border:1px solid var(--glass-border);
                z-index:1000;
                color:var(--text-primary);
                text-align:center;
                max-width:520px
                `;
                success.innerHTML = `
                  <div style="font-size:2.6rem;margin-bottom:8px">🎉</div>
                  <h2 style="margin-bottom:8px;color:var(--primary);font-weight:700">Submitted!</h2>
                  <p style="margin-bottom:18px;line-height:1.6">Your expedition booking has been submitted. Our team will get in touch shortly.</p>
                  <button 
                    onclick="try{window.close()}catch(e){} 
                            if(!window.closed){ window.location.href = window.location.href; }" 
                    class="btn btn-primary">
                    Close
                  </button>
                `;
                document.body.appendChild(success);

                submitBtn.innerHTML = 'Submit Application';
                submitBtn.disabled = false;

                // Optional: reset form
                form.reset();
                currentStep = 1;
                showStep(currentStep);
            }, 800);
        } else {
            alert(data.message || "Something went wrong. Please try again.");
            submitBtn.innerHTML = 'Submit Application';
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error(error);
        alert("Submission failed. Please try again.");
        submitBtn.innerHTML = 'Submit Application';
        submitBtn.disabled = false;
    });


    //   const style = document.createElement('style');
    //   style.textContent='@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}';
    //   document.head.appendChild(style);

    //   setTimeout(()=>{
    //     const success = document.createElement('div');
    //     success.style.cssText = `position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(255,255,255,.95);
    //       backdrop-filter:blur(25px);padding:28px;border-radius:18px;box-shadow:0 25px 70px rgba(79,51,37,.2);
    //       border:1px solid var(--glass-border);z-index:1000;color:var(--text-primary);text-align:center;max-width:520px`;
    //     success.innerHTML = `
    //       <div style="font-size:2.6rem;margin-bottom:8px">🎉</div>
    //       <h2 style="margin-bottom:8px;color:var(--primary);font-weight:700">Submitted!</h2>
    //       <p style="margin-bottom:18px;line-height:1.6">Your expedition booking has been submitted. Our team will get in touch shortly.</p>
    //       <button onclick="this.parentElement.remove()" class="btn btn-primary">Close</button>
    //     `;
    //     document.body.appendChild(success);

    //     submitBtn.innerHTML = 'Submit Application';
    //     submitBtn.disabled = false;
    //     // Optional: form.reset(); currentStep=1; showStep(currentStep);
    //   }, 1800);
    });

    // init
    showStep(currentStep);

    // Subtle hover on fields
    document.querySelectorAll('input,select,textarea').forEach(f=>{
      f.addEventListener('mouseenter',function(){ if(!this.matches(':focus')) this.style.transform='translateY(-1px)'; });
      f.addEventListener('mouseleave',function(){ if(!this.matches(':focus')) this.style.transform='translateY(0)'; });
    });

    // Image icon js code
    document.addEventListener("DOMContentLoaded", function() {
      const input = document.getElementById("profile-input");
      const previewBox = document.getElementById("profile-preview");
      const previewImg = document.getElementById("profile-img-preview");
      const fileNameSpan = document.getElementById("profile-file-name");
      const removeBtn = document.getElementById("remove-profile");
      const uploadOverlay = document.getElementById("upload-overlay");
    
      // Ensure old image shows
      if (previewImg && previewImg.src && previewImg.src.trim() !== "") {
        previewImg.style.display = "block";
        previewBox.style.display = "inline-block";
        if (removeBtn) removeBtn.style.display = "block";
        if (uploadOverlay) uploadOverlay.style.display = "block";
      }
    
      // Open file input on click (overlay or image)
      if (uploadOverlay) {
        uploadOverlay.addEventListener("click", function() {
          input.click();
        });
      }
      if (previewImg) {
        previewImg.addEventListener("click", function() {
          input.click();
        });
      }
    
      // File change preview
      if (input) {
        input.addEventListener("change", function () {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
              previewImg.src = ev.target.result;
              previewImg.style.display = "block";
              fileNameSpan.textContent = file.name;
              previewBox.style.display = "inline-block";
              removeBtn.style.display = "block";
              if (uploadOverlay) uploadOverlay.style.display = "block";
            };
            reader.readAsDataURL(file);
          }
        });
      }
    
      // Remove image
      if (removeBtn) {
        removeBtn.addEventListener("click", function () {
          input.value = "";
          previewImg.src = "";
          previewImg.style.display = "none";
          fileNameSpan.textContent = "";
          previewBox.style.display = "none";
          input.style.display = "block";
          input.required = true;
          const oldHidden = document.querySelector("input[name='old_profile']");
          if (oldHidden) oldHidden.remove();
        });
      }
    });
    
    
    
  