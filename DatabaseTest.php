<?php

namespace FpDbTest;

use Exception;
use FpDbTest\Interface\DatabaseInterface;

class DatabaseTest
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function testBuildQuery(): void
    {
        $results = [];

        //$results[] = $this->db->buildQuery('SELECT name FROM users WHERE user_id = 1');
        //
        //$results[] = $this->db->buildQuery(
        //    'SELECT * FROM users WHERE name = ? AND block = 0',
        //    ['Jack']
        //);
        //
        //$results[] = $this->db->buildQuery(
        //    'SELECT ?# FROM users WHERE user_id = ?d AND block = ?d',
        //    [['name', 'email'], 2, true]
        //);
        //
        //$results[] = $this->db->buildQuery(
        //    'UPDATE users SET ?a WHERE user_id = -1',
        //    [['name' => 'Jack', 'email' => null]]
        //);

        foreach ([null, true] as $block) {
            $results[] = $this->db->buildQuery(
                'SELECT name FROM users WHERE ?# IN (?a){ AND block = ?d AND block = ?f}{ AND block = ?s}',
                ['user_id', [1, 2, 3], $block ?? $this->db->skip(), '0.01', 'ntcn']
            );
        }


        $correct = [
            'SELECT name FROM users WHERE user_id = 1',
            'SELECT * FROM users WHERE name = \'Jack\' AND block = 0',
            'SELECT `name`, `email` FROM users WHERE user_id = 2 AND block = 1',
            'UPDATE users SET `name` = \'Jack\', `email` = NULL WHERE user_id = -1',
            'SELECT name FROM users WHERE `user_id` IN (1, 2, 3)',
            'SELECT name FROM users WHERE `user_id` IN (1, 2, 3) AND block = 1',
        ];

        foreach ($results as $key => $result) {
            if ($result != $correct[$key]) {
                echo $result,"\t!==\t", $correct[$key], "\n";
            }
        }

        exit();

        if ($results !== $correct) {
            throw new Exception('Failure.');
        }
    }
}
