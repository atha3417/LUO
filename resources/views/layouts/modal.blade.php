<div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <form action="/change-password" id="form-password" method="post">
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Password</h4>
                </div>
                <div class="modal-body">
                    <div id="message"></div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" class="form-control" id="old-password" name="old-password"
                                placeholder="password lama kamu">
                        </div>
                        <div class="form-group">
                            <label>Password baru</label>
                            <input type="password" class="form-control" id="new-password" name="new-password"
                                placeholder="minimal 7 karakter (huruf dan angka)">
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password-confirmation"
                                name="password-confirmation" placeholder="sama dengan password baru">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="submit_button" id="password-submit">Ubah
                        Password</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
