<?php
function salat_time_view(){
    ob_start();
    ?>

    <div id="app" class="samadhan-salat-time">
        <table>
            <thead>
            <tr>
                <th class="smdn-custom-th">Salat</th>
                <th class="smdn-custom-th">Iqamah</th>
            </tr>
            </thead>
            <tbody>

            <tr v-if="info!==null" v-for="data in info.data">
                <td class="smdn-custom-label">{{data.Salat}}</td>
                <td><input v-model="data.Iqamah" @change="saveChanges" class="smdn-custom-input" size="8" ></td>
            </tr>
            </tbody>
        </table>
    </div>

    <?php
   return ob_get_clean();

}


