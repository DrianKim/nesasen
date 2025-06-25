<style>
    .modal-reminder {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
    }

    .modal-content-reminder {
        background-color: var(--color-white);
        color: var(--color-dark);
        padding: 2rem;
        border-radius: 1rem;
        width: 90%;
        max-width: 400px;
        text-align: center;
        box-shadow: var(--box-shadow);
    }

    .modal-header-reminder {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
    }

    .modal-body-reminder .coming-soon {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
    }

    .modal-footer-reminder {
        margin-top: 1.5rem;
    }

    .modal-footer-reminder button {
        background-color: #43c6c9;
    }
    
    .dark-mode-variables.modal-footer-reminder button {
        background-color: #43c6c9;
    }

    .dark-mode-variables .modal-content-reminder {
        background-color: #181a1e;
        color: #fff;
    }
</style>


<!-- Modal Reminder -->
<div class="modal-reminder" id="modalReminder">
    <div class="modal-content-reminder">
        <!-- Header -->
        <div class="modal-header-reminder">
            <span class="material-icons-sharp">notifications</span>
            Tambah Reminder
        </div>

        <!-- Body -->
        <div class="modal-body-reminder">
            <div class="coming-soon">
                <span class="material-icons-sharp">hourglass_empty</span>
                <h3>Coming Soon</h3>
                <p>Fitur ini masih dalam tahap pengembangan.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer-reminder">
            <button onclick="closeReminderModal()" class="btn-secondary">
                <span class="material-icons-sharp">close</span> Tutup
            </button>
        </div>
    </div>
</div>
