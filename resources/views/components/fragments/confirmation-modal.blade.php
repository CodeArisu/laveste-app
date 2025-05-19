 <div class="modal fade mt-5" tabindex="-1" id="confirmationModal" aria-labelledby="confirmationModal" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="modal-icon">
                     <img src="/assets/icons/check.png" alt="check Icon">
                 </div>
                 <p class="modal-text">Are you sure you want to<br><strong>Proceed?</strong></p>
             </div>
             <div class="modal-buttons pt-5">
                 <button type="button" data-bs-dismiss="modal" class="modal-cancel ">Cancel</button>
                 {{-- <button type="button" class="modal-confirm">Archive</button> --}}
                 {{ $slot }}
             </div>
         </div>
     </div>
 </div>
