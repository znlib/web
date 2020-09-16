<?php

namespace ZnLib\Web\Widgets;

use ZnLib\Web\Widgets\Base\BaseWidget;

class ModalWidget extends BaseWidget
{

    public $tagId;
    public $header;
    public $body;
    public $footer;

    public function render(): string
    {
        return '
        <div class="modal fade" id="' . $this->tagId . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ' . $this->header . '
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ' . $this->body . '
                </div>
                <div class="modal-footer">
                    ' . $this->footer . '
                </div>
            </div>
        </div>
    </div>
        ';
    }

}