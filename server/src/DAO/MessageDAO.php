<?php

namespace App\DAO;

use Exception;

final class MessageDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($message)
    {
        $message = json_decode($message, true);
        $collection = $this->db->chat->groups;

        if($collection){
            if (array_key_exists('groupID', $message)) {
                try {
                    $insert = $collection->findOneAndUpdate(
                        ['id' => $message['groupID']],
                        [
                            '$push' => [
                                'messages' => [
                                    '$each' => [$message],
                                    '$position' => 0,
                                ],
                            ],
                        ]
                    );
                
                    if ($insert) {
                        return ['status' => 'success', 'message' => 'Insert message success'];
                    } else {
                        return ['status' => 'error', 'message' => 'Failed to insert message'];
                    }
                } catch (\Exception $e) {
                    return ['status' => 'error', 'message' => $e->getMessage()];
                }
            } else {
                return ['status' => 'success', 'message' => 'groupID not found'];
            }
        }else {
            return ['status' => 'error','message' => 'Collection not found'];
        }
    }
}
