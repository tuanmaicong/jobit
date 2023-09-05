@extends('employer.layout.index')
@section('main-employer')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Danh sách gói cước</h5>
            <a href="{{ route('employer.package.create') }}" class="btn btn-primary">Mua gói cước</a>
            <div class="table-responsive mt-4">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">Tên gói cước</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Cấp bậc</th>
                            <th scope="col">Thời gian hết hạn</th>
                            <th scope="col">Trạng Thái</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pachageForEmployer as $item)
                            <tr>
                                <td>{{ $item->name_package }}</td>
                                <td>{{ number_format($item->price) }}đ</td>
                                <td>{{ $item->lever_package }}</td>
                                <td>{{ $item->end_time }}</td>
                                <td>
                                    <span
                                        class="badge  p-1 {{ $item->status == 1 ? 'bg-success text-white' : 'bg-secondary text-white' }}">{{ $item->status_package }}</span>
                                </td>
                                <td>
                                    @if ($item->status == 2)
                                        <btn-extension
                                            :message-confirm="{{ json_encode('Bạn có chắc muốn gia hạn với mức giá ' . number_format($item->price) . 'đ' . ' không ?') }}"
                                            :delete-action="{{ json_encode(route('employer.package.updateTimePayment', $item->id)) }}"
                                            :price="{{ json_encode($item->price) }}"
                                            :acc-payment="{{ json_encode($accPayment) }}">
                                        </btn-extension>
                                    @endif
                                    <btn-upgrade
                                        :message-confirm="{{ json_encode('Bạn có chắc muốn nâng cấp gói cước?') }}"
                                        :delete-action="{{ json_encode(route('employer.package.upgradePackage', $item->id)) }}"
                                        :checkPackage="{{ json_encode($checkPackage->price) }}"
                                        :acc-payment="{{ json_encode($accPayment) }}"
                                        :package="{{ json_encode($package) }}">
                                    </btn-upgrade>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
