<div class="container-fluid">
    <?php echo $this->Session->flash(); ?>
    <div class="row-fluid">

        <div class="btn-toolbar">
            <button id="btnewpage" class="btn btn-primary">
                <i class="icon-plus"></i> Compose New Message
            </button>
            <div class="btn-group"></div>
        </div>
        <?php echo $this->element('message_menu'); ?>
        <div id="userupdate">

            <div class="well"><input type="text" id="kwd_search" value="" placeholder="Type here to search"/>

                <form id="mainform" method="post"
                      action="<? echo SITE_URL; ?>admin/Pages/list">
                    <input type="hidden" name="action" id="action">
                    <table class="table tablesorter" id="my-table">
                        <thead>
                        <tr>
                            <th class="nosort header"><input type="checkbox"
                                                             name="checkall" id="check" onclick="checkAll()" value="1">
                            </th>
                            <th>subject</th>

                            <th>Created</th>
                            <th class="nosort header" width="65">Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($outbox_message)) {
                            foreach ($outbox_message as $data) {
                                $id = $data['MessageIndex']['thread_id'];

                                $count = ($data[0]['count'] > 1) ? '(' . $data[0]['count'] . ')' : '';

                                ?>
                                <tr>
                                    <td id="<?php echo $id; ?>" valign="top"><input id="<?php echo $id; ?>"
                                                                                    type="checkbox" name="deleteall[]"
                                                                                    value="<?php echo $id; ?>"/></td>
                                    <td><strong><a
                                                href="<?php echo SITE_URL . 'admin/messages/view/' . $id ?>"><?php echo $this->Custom->getString($data['Message']['subject']) . $count; ?></a></strong>
                                        <br><?php echo $this->Custom->getString($data['Message']['body'], 64); ?>
                                    </td>

                                    <td><?php echo $data['Message']['created']; ?></td>
                                    <td>
                                        <a onclick="return confirm('Are you sure you want to delete this thread?')"
                                           href="<?php echo SITE_URL . 'admin/messages/delete/thread/' . $id; ?>"
                                           title="Delete" class="icon-remove"></a>

                                    </td>

                                </tr>
                            <?php
                            }

                        } else {
                            echo '<tr><td colspan=5>Rocord Not found</td></tr>';

                        }

                        ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="pagination">
            <ul>
                <li>
                    <?php echo $this->Paginator->prev(('Previous'), array(), null, array('class' => 'prev disabled')); ?>
                </li>
                <li>
                    <?php echo $this->Paginator->numbers(array('separator' => '')); ?>
                </li>
                <li>
                    <?php echo $this->Paginator->next(('Next'), array(), null, array('class' => 'prev disabled')); ?>
                </li>
            </ul>
        </div>

    </div>
</div>

<script>


    $('#btnewpage').click(function () {
        document.location.href = '<?php echo SITE_URL."admin/messages/new"?>';
    });

</script>