<?php
/**
 * Pengaturan Penulis & Foto Profil Khusus untuk Post Type Blog (Kolom Opini & Ruang Literasi GTK)
 * Memisahkan tampilan Foto Penulis (di Beranda/Card) dan Banner Ilustrasi 1400x600 (di Dalam Artikel)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: Ambil data penulis artikel blog (Foto, Nama, Jabatan)
 */
function mtsn1_get_blog_author_data( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $name  = get_post_meta( $post_id, '_author_name', true );
    $role  = get_post_meta( $post_id, '_author_role', true );
    $photo = get_post_meta( $post_id, '_author_photo', true );

    // Fallback jika belum diisi custom field
    if ( empty( $name ) ) {
        $post = get_post( $post_id );
        if ( $post ) {
            $author_user = get_userdata( $post->post_author );
            if ( ! $author_user || $author_user->user_login === 'admin' ) {
                $name = 'Drs. Sihabbudin';
                $role = ! empty( $role ) ? $role : 'Kepala MTsN 1 Blitar';
            } else {
                $name = $author_user->display_name;
                $role = ! empty( $role ) ? $role : 'GTK MTsN 1 Blitar';
            }
        } else {
            $name = 'Drs. Sihabbudin';
            $role = 'Kepala MTsN 1 Blitar';
        }
    }

    if ( empty( $role ) ) {
        if ( stripos( $name, 'Sihab' ) !== false ) {
            $role = 'Kepala MTsN 1 Blitar';
        } else {
            $role = 'GTK MTsN 1 Blitar';
        }
    }

    if ( empty( $photo ) ) {
        if ( stripos( $name, 'Sihab' ) !== false ) {
            $photo = home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
        } else {
            // Cek apakah ada post GTK dengan judul sama
            global $wpdb;
            $gtk_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'gtk' AND post_title = %s AND post_status = 'publish' LIMIT 1",
                $name
            ) );
            if ( $gtk_id && has_post_thumbnail( $gtk_id ) ) {
                $photo = get_the_post_thumbnail_url( $gtk_id, 'medium' );
            } else {
                // Fallback default avatar guru
                $photo = home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
            }
        }
    }

    return array(
        'name'  => $name,
        'role'  => $role,
        'photo' => $photo,
    );
}

/**
 * Daftarkan Meta Box di editor postingan Blog
 */
function mtsn1_add_blog_author_meta_box() {
    add_meta_box(
        'mtsn1_blog_author_box',
        '✍️ Profil Penulis Kolom Opini (Tampilan Beranda)',
        'mtsn1_render_blog_author_meta_box',
        'blog',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'mtsn1_add_blog_author_meta_box' );

/**
 * Render Form Meta Box
 */
function mtsn1_render_blog_author_meta_box( $post ) {
    wp_nonce_field( 'mtsn1_blog_author_nonce_action', 'mtsn1_blog_author_nonce' );

    $author_data = mtsn1_get_blog_author_data( $post->ID );
    $name  = get_post_meta( $post->ID, '_author_name', true );
    $role  = get_post_meta( $post->ID, '_author_role', true );
    $photo = get_post_meta( $post->ID, '_author_photo', true );

    // Gunakan value fallback jika kosong untuk kenyamanan form
    if ( empty( $name ) )  $name  = $author_data['name'];
    if ( empty( $role ) )  $role  = $author_data['role'];
    if ( empty( $photo ) ) $photo = $author_data['photo'];
    ?>
    <style>
      .mtsn1-author-meta-wrap {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding: 10px 0;
        font-family: inherit;
      }
      .mtsn1-author-photo-preview {
        width: 120px;
        height: 150px;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }
      .mtsn1-author-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .mtsn1-author-fields {
        flex: 1;
      }
      .mtsn1-form-row {
        margin-bottom: 12px;
      }
      .mtsn1-form-row label {
        display: block;
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 13px;
        color: #1e293b;
      }
      .mtsn1-form-row input[type="text"] {
        width: 100%;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
      }
      .mtsn1-desc {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
      }
      .mtsn1-tip {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 8px 12px;
        border-radius: 8px;
        color: #065f46;
        font-size: 12px;
        margin-top: 10px;
      }
    </style>

    <div class="mtsn1-author-meta-wrap">
      <div class="mtsn1-author-photo-preview" id="author_photo_preview">
        <?php if ( $photo ) : ?>
          <img src="<?php echo esc_url( $photo ); ?>" alt="Preview">
        <?php else : ?>
          <span style="color:#94a3b8; font-size:11px; text-align:center;">Foto Penulis</span>
        <?php endif; ?>
      </div>

      <div class="mtsn1-author-fields">
        <div class="mtsn1-form-row">
          <label for="mtsn1_author_name">Nama Lengkap & Gelar Penulis:</label>
          <input type="text" id="mtsn1_author_name" name="mtsn1_author_name" value="<?php echo esc_attr( $name ); ?>" placeholder="Contoh: Drs. Sihabbudin / Bpk/Ibu Guru...">
        </div>

        <div class="mtsn1-form-row">
          <label for="mtsn1_author_role">Jabatan / Satuan Tugas / Mapel:</label>
          <input type="text" id="mtsn1_author_role" name="mtsn1_author_role" value="<?php echo esc_attr( $role ); ?>" placeholder="Contoh: Kepala MTsN 1 Blitar / Guru Bahasa Indonesia">
        </div>

        <div class="mtsn1-form-row">
          <label for="mtsn1_author_photo">URL Foto Penulis (Format Portrait):</label>
          <div style="display:flex; gap:8px;">
            <input type="text" id="mtsn1_author_photo" name="mtsn1_author_photo" value="<?php echo esc_attr( $photo ); ?>" placeholder="https://...">
            <button type="button" class="button button-secondary" id="mtsn1_upload_photo_btn">Pilih / Upload Foto</button>
          </div>
          <p class="mtsn1-desc">Foto portrait ini akan muncul di kartu Beranda depan (Kolom Opini & Ruang Literasi GTK).</p>
        </div>

        <div class="mtsn1-tip">
          💡 <strong>Tips Tampilan:</strong>
          <ul style="margin:4px 0 0 16px; list-style-type:disc;">
            <li><strong>Foto Penulis (di atas):</strong> Tampil di <em>Beranda / Kartu Opini Depan</em> sehingga langsung dikenali pembaca.</li>
            <li><strong>Featured Image (Gambar Unggulan):</strong> Gunakan banner landscape 1400 × 600 px untuk tampil megah di <em>dalam artikel tunggal</em>.</li>
          </ul>
        </div>
      </div>
    </div>

    <script>
      jQuery(document).ready(function($){
        var frame;
        $('#mtsn1_upload_photo_btn').on('click', function(e){
          e.preventDefault();
          if (frame) {
            frame.open();
            return;
          }
          frame = wp.media({
            title: 'Pilih Foto Penulis Kolom Opini',
            button: { text: 'Gunakan Foto Ini' },
            multiple: false
          });
          frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#mtsn1_author_photo').val(attachment.url);
            $('#author_photo_preview').html('<img src="' + attachment.url + '" alt="Preview">');
          });
          frame.open();
        });

        $('#mtsn1_author_photo').on('input change', function(){
          var val = $(this).val();
          if (val) {
            $('#author_photo_preview').html('<img src="' + val + '" alt="Preview">');
          }
        });
      });
    </script>
    <?php
}

/**
 * Simpan Data Meta Box
 */
function mtsn1_save_blog_author_meta( $post_id ) {
    if ( ! isset( $_POST['mtsn1_blog_author_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_blog_author_nonce'], 'mtsn1_blog_author_nonce_action' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['mtsn1_author_name'] ) ) {
        update_post_meta( $post_id, '_author_name', sanitize_text_field( $_POST['mtsn1_author_name'] ) );
    }

    if ( isset( $_POST['mtsn1_author_role'] ) ) {
        update_post_meta( $post_id, '_author_role', sanitize_text_field( $_POST['mtsn1_author_role'] ) );
    }

    if ( isset( $_POST['mtsn1_author_photo'] ) ) {
        update_post_meta( $post_id, '_author_photo', esc_url_raw( $_POST['mtsn1_author_photo'] ) );
    }
}
add_action( 'save_post_blog', 'mtsn1_save_blog_author_meta' );
