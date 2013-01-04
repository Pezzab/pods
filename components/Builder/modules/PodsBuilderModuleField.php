<?php
/**
 * @package Pods\Components
 * @subpackage Builder
 */
if ( !is_admin() || !class_exists( 'LayoutModule' ) || class_exists( 'PodsBuilderModuleField' ) )
    return;

class PodsBuilderModuleField extends LayoutModule {

    var $_name = '';
    var $_var = 'pods-builder-field';
    var $_description = '';
    var $_editor_width = 500;
    var $_can_remove_wrappers = true;

    /**
     * Register the Module
     */
    public function PodsBuilderModuleField () {
        $this->_name = __( 'Pods Field Value', 'pods' );
        $this->_description = __( "Display a single Pod item's field value", 'pods' );
        $this->module_path = dirname( __FILE__ );

        $this->LayoutModule();
    }

    /**
     * Set default variables
     *
     * @param $defaults
     *
     * @return mixed
     */
    function _get_defaults ( $defaults ) {
        $new_defaults = array(
            'name' => '',
            'slug' => 'none',
            'field' => ''
        );

        return ITUtility::merge_defaults( $new_defaults, $defaults );
    }

    /**
     * Output something before the table form
     *
     * @param object $form Form class
     * @param bool $results
     */
    function _before_table_edit ( $form, $results = true ) {
?>
    <p><?php _e( "Display a single Pod item's field value", 'pods' ); ?></p>
<?php
    }

    /**
     * Output something at the start of the table form
     *
     * @param object $form Form class
     * @param bool $results
     */
    function _start_table_edit ( $form, $results = true ) {
        $api = pods_api();
        $all_pods = $api->load_pods();

        $pod_types = array();

        foreach ( $all_pods as $pod ) {
            $pod_types[ $pod[ 'name' ] ] = $pod[ 'label' ];
        }
?>
    <tr>
        <td valign="top">
            <label for="pod_type"><?php _e( 'Pod', 'pods' ); ?></label>
        </td>
        <td>
            <?php
                if ( 0 < count( $all_pods ) )
                    $form->add_drop_down( 'pod_type', $pod_types );
                else
                    echo '<strong class="red">' . __( 'None Found', 'pods' ) . '</strong>';
            ?>
            <?php $form->add_text_box( 'pod_type' ); ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <label for="slug"><?php _e( 'Slug or ID', 'pods' ); ?></label>
        </td>
        <td>
            <?php $form->add_text_box( 'slug' ); ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <label for="slug"><?php _e( 'Field', 'pods' ); ?></label>
        </td>
        <td>
            <?php $form->add_text_box( 'field' ); ?>
        </td>
    </tr>
<?php
    }

    /**
     * Module Output
     */
    function _render ( $fields ) {
        $args = array(
            'name' => trim( pods_var_raw( 'pod_type', $fields, '' ) ),
            'slug' => trim( pods_var_raw( 'slug', $fields, '' ) ),
            'field' => trim( pods_var_raw( 'field', $fields, '' ) )
        );

        if ( 0 < strlen( $args[ 'name' ] ) && 0 < strlen( $args[ 'slug' ] ) && 0 < strlen( $args[ 'field' ] ) )
            echo pods_shortcode( $args, ( isset( $content ) ? $content : null ) );
    }
}

new PodsBuilderModuleField();