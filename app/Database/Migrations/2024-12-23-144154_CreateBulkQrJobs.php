<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBulkQrJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'total_files' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'processed_files' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'zip_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'failed'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('bulk_qr_jobs');
    }

    public function down()
    {
        $this->forge->dropTable('bulk_qr_jobs');
    }
}
