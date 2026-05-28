<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name'     => 'InvoiceFlow Admin',
            'email'    => 'admin@invoiceflow.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Regular user
        $user = User::create([
            'name'     => 'Jane Freelancer',
            'email'    => 'user@invoiceflow.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Create 3 clients for Jane
        $clients = [
            Client::create(['user_id'=>$user->id,'name'=>'Acme Corp','email'=>'billing@acme.com','company'=>'Acme Corporation','phone'=>'+1-555-0101','address'=>'123 Main St, New York, NY']),
            Client::create(['user_id'=>$user->id,'name'=>'TechStart Ltd','email'=>'accounts@techstart.io','company'=>'TechStart Ltd','phone'=>'+1-555-0202']),
            Client::create(['user_id'=>$user->id,'name'=>'Sarah Johnson','email'=>'sarah@sjdesign.com','phone'=>'+44-555-0303']),
        ];

        // Create invoices for each client
        $invoiceData = [
            ['client'=>$clients[0],'status'=>'paid','total'=>3500.00,'items'=>[['Web Development','1','3500.00']]],
            ['client'=>$clients[0],'status'=>'sent','total'=>1200.00,'items'=>[['Monthly SEO Package','1','1200.00']]],
            ['client'=>$clients[1],'status'=>'overdue','total'=>2800.00,'items'=>[['UI Design','2','1200.00'],['Consultation','4','100.00']]],
            ['client'=>$clients[1],'status'=>'draft','total'=>600.00,'items'=>[['Logo Design','1','600.00']]],
            ['client'=>$clients[2],'status'=>'viewed','total'=>950.00,'items'=>[['Copywriting','5','190.00']]],
        ];

        foreach ($invoiceData as $i => $data) {
            $subtotal = collect($data['items'])->sum(fn($it) => $it[1] * $it[2]);
            $invoice = Invoice::create([
                'user_id'        => $user->id,
                'client_id'      => $data['client']->id,
                'invoice_number' => 'INV-2024-' . str_pad($i+1, 5, '0', STR_PAD_LEFT),
                'status'         => $data['status'],
                'subtotal'       => $subtotal,
                'tax_rate'       => 0,
                'tax_amount'     => 0,
                'total'          => $subtotal,
                'currency'       => 'USD',
                'issue_date'     => now()->subDays(rand(5, 60)),
                'due_date'       => now()->addDays(rand(-10, 30)),
                'paid_at'        => $data['status'] === 'paid' ? now()->subDays(5) : null,
                'sent_at'        => in_array($data['status'],['sent','viewed','paid']) ? now()->subDays(10) : null,
            ]);

            foreach ($data['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item[0],
                    'quantity'    => $item[1],
                    'unit_price'  => $item[2],
                    'total'       => $item[1] * $item[2],
                ]);
            }
        }
    }
}
